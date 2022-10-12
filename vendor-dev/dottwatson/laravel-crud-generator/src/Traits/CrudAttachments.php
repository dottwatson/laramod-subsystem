<?php

namespace Dottwatson\CrudGenerator\Traits;

use Dottwatson\CrudGenerator\Models\Attachment;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use SplFileInfo;
use Symfony\Component\Mime\FileinfoMimeTypeGuesser;
use Illuminate\Support\Str;

trait CrudAttachments{


    /**
     * get all available attachments
     *
     * @return array Pair att type=>att configuration
     */
    public function getAvailableAttachements()
    {
        return [];
    }

    /**
     * get attachment conigurationor part of it with dot notation
     *
     * @param string $type
     * @param string|null $needle
     * @return string|array|null
     */
    public static function getAttachmentConfig(string $type,string $needle = null,$default = null)
    {
        $availableAttachments = (new static)->getAvailableAttachements();

        if(!array_key_exists($type,$availableAttachments)){
            throw new \Exception("{$type} is not a valid attachment for ".static::class);
        }

        $handlerInfo = $availableAttachments[$type]['handler'];

        if(is_array($handlerInfo)){
            $handler = new \Dottwatson\CrudGenerator\Crud\Handlers\AttachmentHandler;
            $handler->setUSerConfig($handlerInfo);
        }
        elseif(is_object($handlerInfo)){
            $handler = $handlerInfo;
            $handler->init();
        }
        else{
            $handler = new $handlerInfo;
            $handler->init();
        }
    

        return $handler->getConfig($needle,$default);
    }


    /**
     * add several attachments
     *
     * @param string $attachmentType
     * @param array $attachments
     * @return array
     */
    public function addAttachments(string $attachmentType,array $attachments = [])
    {
        $results = [];
        foreach($attachments as $attachment){
            $results[] = $this->addAttachment($attachmentType,$attachment);
        }

        return $results;
    }

    /**
     * assigna  previouse attachment to this model
     *
     * @param int|Attachment $attachment
     * @param string|null $customPath
     * @param array $pathParameters
     * @return bool
     */
    public function addAttachment(string $type,$attachment,string $customPath = null,array $pathParameters = [])
    {
        $config                 = static::getAttachmentConfig($type);
        $attachmentModelName    = $config['model'];
        $attachment             = ($attachment instanceOf Attachment)
            ?$attachment
            :($attachmentModelName::find((int)$attachment));
        
        $pathStr = ($customPath !== null)
            ?$customPath
            :$config['storage']['path'];

        $endPath   = $this->buildAttachmentPath($pathStr,$pathParameters,$attachment);
        $reference = $this->buildAttachmentReference($config['reference'],$pathParameters,$attachment);

        if($attachment->path != $endPath){
            $newFullPath = "$endPath/{$attachment->name}";
            Storage::disk($attachment->disk)->move($attachment->fullPath,$newFullPath);
        }

        return $attachment->update([
            'path'      => $endPath,
            'reference' => $reference
        ]);

    }

    /**
     * remove several attachments
     *
     * @param string $attachmentType
     * @param array $attachments
     * @return array
     */
    public function removeAttachments(string $attachmentType,array $attachments = [])
    {
        $results = [];
        foreach($attachments as $attachment){
            $results[] = $this->removeAttachment($attachmentType,$attachment);
        }

        return $results;
    }

    /**
     * remove and attachment
     *
     * @param string $type
     * @param int|Attachment $attachment
     * @return static
     */
    public function removeAttachment(string $type,$attachment)
    {
        $config                 = static::getAttachmentConfig($type);
        $attachmentModelName    = $config['model'];
        $attachment             = ($attachment instanceOf Attachment)
            ?$attachment
            :($attachmentModelName::find((int)$attachment));

        return $attachment->delete();
    }

    /**
     * relations on attachment
     *
     * @return void
     */
    public function attachments()
    {
        $relation       = $this->hasMany(Attachment::class,'reference');
        $relationQuery  = $relation->getQuery();
        $tables         = [];

        foreach($this->getAvailableAttachements() as $type=>$attachmentConfiguration){
            $config                 = static::getAttachmentConfig($type);
            $attachmentModelName    = $config['model'];
            $attachmentReference    = $this->buildAttachmentReference($config['reference']);
            $table                  = (new $attachmentModelName)->getTable();
            $uuId                   = md5("{$table}|{$attachmentReference}");

            if(!array_key_exists($uuId,$tables)){
                $tables[$uuId] = ['table'=>$table,'reference'=>$attachmentReference,'types'=>[]];
            }
    
            $tables[$uuId]['type'][] = $type; 
        }

        // dd($tables);
        $cnTables = 0;
        foreach($tables as $uuId=>$tableInfo){
            $reference = $tableInfo['reference'];
            $query = DB::table($table)->where('reference',$reference)->whereIn('type',$tableInfo['type']);

            if($cnTables == 0){
                $relationQuery = $query;
            }
            else{
                $relationQuery->union($query);                
            }

            $cnTables++;
        }


        $relation->setQuery($relationQuery);

        return $relation;
    }

    /**
     * parse the request and perform all needed operations like create,remove and update
     *
     * @param Request $request
     * @return void
     */
    public function checkRequestAttachments(Request $request)
    {
        $attachments        = $request->input('attachments',[]);
        $removedAttachments = $request->input('removed_attachments',[]);
        
        foreach($attachments as $attachmentType=>$attachmentTypesIds){
            $this->addAttachments($attachmentType,$attachmentTypesIds['id']);
        }

        foreach($removedAttachments as $attachmentType=>$attachmentTypesIds){
            $this->removeAttachments($attachmentType,$attachmentTypesIds);
        }


        $this->updateAttachments($request);
    }

    /**
     * build an attachment path as a route
     *
     * @param string $path
     * @param array $parameters
     * @return string
     */
    protected function buildAttachmentPath(string $path,$parameters = [],Attachment $attachment = null)
    {
        $parameters = (is_array($parameters))?$parameters:[$parameters];
        preg_match_all('#\{(?P<placeholders>[^\}]+)\}#Usmi',$path,$results);

        if($results['placeholders']){
            foreach($results['placeholders'] as $k=>$placeholder){
                $definedParameter = $parameters[$placeholder] ?? null;
                if($definedParameter === null){
                	$definedParameter = $parameters[$k] ?? null;
                }
                
                if($definedParameter !== null){
                    $value = $definedParameter;
                }
                else{
                    if(str_starts_with($placeholder,'attachment.') && $attachment){
                        $attachmentAttribute = str_replace('attachment.','',$placeholder);
                        $value = (string)$attachment->{$attachmentAttribute} ?? 'undefined';
                    }
                    else{
                        $value = (string)($this->{$placeholder} ?? 'undefined');
                    }
                }
                
                $path = str_replace('{'.$placeholder.'}',$value,$path);
            }
            
            return $path;
        }
    }

    protected function buildAttachmentReference(string $path,$parameters = [],Attachment $attachment = null)
    {
        $parameters = (is_array($parameters))?$parameters:[$parameters];
        preg_match_all('#\{(?P<placeholders>[^\}]+)\}#Usmi',$path,$results);

        if($results['placeholders']){
            foreach($results['placeholders'] as $k=>$placeholder){
                $definedParameter = $parameters[$placeholder] ?? null;
                if($definedParameter === null){
                	$definedParameter = $parameters[$k] ?? null;
                }
                
                if($definedParameter !== null){
                    $value = $definedParameter;
                }
                else{
                    if(str_starts_with($placeholder,'attachment.') && $attachment){
                        $attachmentAttribute = str_replace('attachment.','',$placeholder);
                        $value = (string)$attachment->{$attachmentAttribute} ?? 'undefined';
                    }
                    else{
                        $value = (string)($this->{$placeholder} ?? 'undefined');
                    }
                }
                
                $path = str_replace('{'.$placeholder.'}',$value,$path);
            }
            
            return $path;
        }
    }


    /**
     * update attachents informations declared in GUI
     *
     * @param Request $request
     * @return void;
     */
    protected function updateAttachments(Request $request)
    {
        //attachment_edit[ae_62cbecd05ad34][attachment_title]
        foreach($request->input('attachment_edit',[]) as $uuid=>$attachmentEditInfo){
            $tokenInfo  = decrypt($attachmentEditInfo['_tokenEditAttachment']);
            $title      = $attachmentEditInfo['attachment_title'];

            unset($attachmentEditInfo['_tokenEditAttachment'],$attachmentEditInfo['attachment_title']);

            $attachmentModel = $tokenInfo['model'];
            $attachment = $attachmentModel::findOrFail($tokenInfo['id']);

            $attachment->update([
                'title' => $title,
                'extrafields' => $attachmentEditInfo
            ]);
        }
    }

    /**
     * return attachment types by attachment class name
     *
     * @param string $className
     * @param boolean $onlyFirst
     * @return string|null|array
     */
    public static function getAttachmentTypeByClassName(string $className,bool $onlyFirst = false)
    {
        $results = [];
        $attachmentTypes = (new static)->getAvailableAttachements();
        foreach($attachmentTypes as $type){
            $config = static::getAttachmentConfig($type);
            if($config['model'] == $className){
                $results[] = $type;
            }

        }

        if($onlyFirst){
            return ($results)?array_shift($results):null;
        }

        return $results;
    }

    /**
     * Programmatically make attachment outside crud system
     *
     * @param string $type The attachment type
     * @param string $filePath The path. Is better tath the file is in local
     * @param string $title The file Title, or will be generated from the filename
     * @param array $extrafields the extrafields added to file
     * @return bool
     */
    public function makeAttachment(string $type,string $filePath,string $title = '',array $extrafields = [])
    {
        $config             = static::getAttachmentConfig($type);
        $model              = $config['model'];
        $storage            = Storage::disk($config['storage']['disk']);
        $tempPath           = $config['storage']['temp_path'];

        $fileFullName       = basename($filePath);
        $blocks             = explode('.',$fileFullName);
        $fileExtension      = array_pop($blocks);
        if(!$blocks){
            $fileName       = $fileExtension;
            $fileExtension  = 'bin';
        }
        else{
            $fileName = implode('.',$blocks);
        }


        $downloadContext = stream_context_create([
            "ssl"=>array(
                "verify_peer"       => false,
                "verify_peer_name"  => false,
            ),
        ]);  

        $storage->put("{$tempPath}/{$fileFullName}",file_get_contents($filePath,false,$downloadContext));

        //if file comes from an url, and name has encoded chars
        //clean the filename
        $localFileName = $fileName;
        if(strpos($fileName,'%') !== false){
            $localFileName = rawurldecode($fileName);
        }

        //clean file name
        $localFileName = Str::slug($localFileName);

        if($localFileName != $fileName){
            if($storage->exists("{$tempPath}/{$localFileName}.{$fileExtension}")){
                $storage->delete("{$tempPath}/{$localFileName}.{$fileExtension}");
            }


            $storage->rename(
                "{$tempPath}/{$fileFullName}",
                "{$tempPath}/{$localFileName}.{$fileExtension}"
            );
        }

        $localFile = $storage->path("{$tempPath}/{$localFileName}.{$fileExtension}");

        $file       = new SplFileInfo($localFile);
        $mimeType   = (new FileinfoMimeTypeGuesser())->guessMimeType($localFile);
        if(!$mimeType){
            $mimeType = 'application/octet-stream';
        }

        $data = [
            'model'                 => $model,
            'original_name'         => $file->getFilename(),
            'original_extension'    => $file->getExtension(),
            'mime_type'             => $mimeType,
            'is_valid'              => 1,
            'error'                 => 0,
            'error_message'         => '',
            'disk'                  => $config['storage']['disk'],
            'name'                  => $file->getFilename(),
            'size'                  => $file->getSize(),
            'type'                  => $type
        ];

        $title  = ($title)
            ?$title
            :basename($file->getFilename(),'.'.$file->getExtension());        

        $extrafieldsData = $extrafields;

        $path    = rtrim($config['storage']['temp_path'],'/');

        $data = array_merge($data,[
            'title'     => $title,
            'name'      => basename($localFile),
            'path'      => $path,
            'extrafields' => $extrafieldsData
        ]);

        $attachment = $model::create($data);

        return $this->addAttachment($type,$attachment);
    }

}
