<?php
namespace Dottwatson\CrudGenerator\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

use Illuminate\Support\Facades\DB;

class AttachmentController extends Controller
{
    protected $owner;
    protected $ownerId;
    protected $config;
    protected $type;
    protected $attachmentClassName;  

    protected $attachmentKeySearch;

    protected function readTokenAttachment()
    {
        $request        = request();
        $data           = decrypt($request->input('_tokenAttachment'));
        $this->config   = $data['config'];
        
        $ownerClass     = $data['owner'];
        $this->ownerId  = $data['owner_id'];

        $this->owner = ($this->ownerId)
            ?(new $ownerClass)
            :$ownerClass::find($this->ownerId);

        $this->type                 = $this->getConfig('type');
        $this->attachmentClassName  = $this->getConfig('model');
    }


    protected function readTokenFile()
    {
        $request        = request();
        $data           = decrypt($request->input('_tokenFile'));
        // dd($data);
        
        $ownerClass     = $data['owner'];
        $this->ownerId  = $data['owner_id'];

        $this->owner = ($this->ownerId)
            ?(new $ownerClass)
            :$ownerClass::find($this->ownerId);


        $this->attachmentClassName = $data['model'];
        $this->attachmentKeySearch = $data['key'];
    }



    /**
     * handle upload, and choise if is a full file or a chunk
     *
     * @param Request $request
     */
    public function upload(Request $request)
    {
        $this->readTokenAttachment();

        $file = $request->file('file');

        $data = [
            'model'                 => $this->attachmentClassName,
            'original_name'         => $file->getClientOriginalName(),
            'original_extension'    => $file->getClientOriginalExtension(),
            'mime_type'             => $file->getClientMimeType(),
            'is_valid'              => $file->isValid(),
            'error'                 => $file->getError(),
            'error_message'         => ($file->getError() > 0)?$file->getErrorMessage():'',
            'disk'                  => $this->getConfig('storage.disk'),
            // 'path'                  => $this->getConfig('storage.teemp_path'),
            'name'                  => $file->getFilename(),
            'size'                  => $file->getSize(),
            'type'                  => $this->type
        ];

        if($request->input('dztotalchunkcount',false) !== false){
            return $this->handleChunks($request,$data);
        }
        else{
            return $this->handleUpload($request,$data);
        }
    }

    /**
     * handle single complete file upload
     *
     * @param Request $request
     * @param array $data
     * @param  $file
     * @return Response
     */
    public function handleUpload(Request $request,array $data = [],$file = null)
    {

        $model  = $this->attachmentClassName;

        $file   = $request->file('file');
        $title  = $request->input(
            'attachment_title',
            basename(
                $file->getClientOriginalName(),
                '.'.$file->getClientOriginalExtension()
            )        
        );

        $extrafieldsData = $request->input('attachment_extrafields',[]);

        $path    = rtrim($this->getConfig('storage.temp_path'),'/');
        $newName = $file->store($path.'/',$data['disk']);
        $data = array_merge($data,[
            'title'     => $title,
            'name'      => basename($newName),
            'path'      => $path,
            'extrafields' => $extrafieldsData
        ]);

        $attachment = $model::create($data);

        
        return response()->json($attachment->only('is_valid','id'));
    }


    /**
     * if upload is in chunked mode, handle single chunk
     *
     * @param Request $request
     * @param array $data
     * @return Response
     */
    public function handleChunks(Request $request,array $data = []){
        $model          = $this->attachmentClassName;
        $totalChunks    = $request->input('dztotalchunkcount');
        $chunkNumber    = $request->input('dzchunkindex');
        $fileUuid       = $request->input('dzuuid');

        
        $tmpDisk        = Storage::disk($data['disk']);
        $disk           = Storage::disk($data['disk']);
        $chunk          = $request->file('file');

        if(!$tmpDisk->exists($fileUuid)){
            $tmpDisk->makeDirectory($fileUuid);
        }

        $chunk->storeAs($fileUuid,"{$chunkNumber}.part",$data['disk']);

        //check if all chunks are uploaded
        if($chunkNumber == $totalChunks-1){
            //rebuild the file and move it, then save in database
            $rebuiltFileName = "{$fileUuid}/{$fileUuid}.".$data['original_extension'];

            for($n = 0; $n < $totalChunks; $n++ ){
                $appendContent = $tmpDisk->get("{$fileUuid}/{$n}.part");
                file_put_contents($tmpDisk->path($rebuiltFileName),$appendContent,FILE_APPEND|LOCK_EX);
            }

            $data['size'] = $tmpDisk->size($rebuiltFileName);

            $title  = $request->input(
                'attachment_title',
                basename(
                    $chunk->getClientOriginalName(),
                    '.'.$chunk->getClientOriginalExtension()
                )        
            );

            $extrafieldsData = $request->input('attachment_extrafields',[]);
            
            $path    = rtrim($this->getConfig('storage.temp_path'),'/');
            $newName = Str::uuid().'.'.$chunk->getClientOriginalExtension();
            $disk->writeStream("{$path}/{$newName}",$tmpDisk->readStream($rebuiltFileName));

            //remove tmp directory
            $tmpDisk->deleteDirectory($fileUuid);

            $data = array_merge($data,[
                'title'     => $title,
                'name'      => $newName,
                'path'      => $path,
                'extrafields' => $extrafieldsData
            ]);
    
            $attachment = $model::create($data);

            return response()->json($attachment->only('is_valid','id'));
        }
        else{
            return response()->json(['is_valid'=>true,'id'=>'CHUNKED_CONTENT']);
        }
        

    }

    public function download(Request $request)
    {
        $this->readTokenFile();

        $model      = $this->attachmentClassName;
        $attachment = $model::where(
            DB::raw('MD5(CONCAT(id,reference,type))')
            ,$this->attachmentKeySearch
        )->first();


        if(!$attachment){
            abort(404);
        }

        return $attachment->download();
    }

    public function view(Request $request,int $id)
    {
        $this->readTokenFile();

        $model      = $this->attachmentClassName;
        $attachment = $model::where(
            DB::raw('MD5(CONCAT(id,reference,type))')
            ,$this->attachmentKeySearch
        )->first();


        if(!$attachment){
            abort(404);
        }

        return $attachment->view();
    }


    /**
     * get informations from config
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getConfig(string $key = null,$default = null)
    {
        if($key === null){
            return $this->config;
        }
        else{
            $value = Arr::get($this->config,$key,$default);
            return ($value === null)?$default:$value;
        }
    }
}
