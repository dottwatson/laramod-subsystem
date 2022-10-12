<?php 
namespace Dottwatson\CrudGenerator\Form\Fields;

use Dottwatson\CrudGenerator\Crud\Forms\AttachmentExtraFieldsForm;
use Dottwatson\CrudGenerator\Form\Field;
use Dottwatson\CrudGenerator\Models\Attachment;

class Attachments extends Field {

    protected $fixedType = 'file';


    public function getFileUrl(string $type,array $params = [],Attachment $file = null)
    {
        $model          = $this->getParent()->getModel();
        $config         = $model->getAttachmentConfig($this->name);
        $handler        = $this->getFieldHandler();

        if(!array_key_exists($type,$config)){
            throw new \Exception("Route {$type} does not exists as attachment route for ".$this->name);
        }

        return $this->buildConfigUrl($handler->getConfig($type),$params);
    }


    /**
     * get attachment route operation type
     *
     * @param string $key the operation [upload,view,download]
     * @param array $params route params
     * @return string|null
     */
    public function getRoute(string $type,array $params = [])
    {
        $model          = $this->getParent()->getModel();
        $config         = $model->getAttachmentConfig($this->name);
        
        if(!array_key_exists($type,$config['routes'])){
            throw new \Exception("Route {$type} does not exists as attachment route in ".static::class);
        }

        return route($confif['routes'][$type],$params);
    }


    /**
     * the storage disk
     *
     * @return string
     */
    public static function getDisk()
    {
        $configKey  = static::$attachmentsConfigKey;
        $config     = config("attachments.configurations.{$configKey}");
        $storage    = config("attachments.configurations.{$configKey}.storage"); 

        if(!$config || !isset($storage['disk']) || !$storage['disk']){
            return config("attachments.configurations.default.storage.disk");
        }

        return $storage['disk'];
    }

    /**
     * the path of uploaded file
     *
     * @return string
     */
    public static function getPath()
    {
        $configKey  = static::$attachmentsConfigKey;
        $config     = config("attachments.configurations.{$configKey}");
        $storage    = config("attachments.configurations.{$configKey}.storage"); 

        if(!$config || !isset($storage['disk']) || !$storage['path']){
            return config("attachments.configurations.default.storage.path");
        }

        return $storage['path'];
    }

    /**
     * The extra fields
     *
     * @return array
     */
    public static function getFields()
    {
        return [
            ['title','text',['label'=>'Titolo']]
        ];
    }


    /**
     * the view of field
     *
     * @return void
     */
    protected function getTemplate()
    {
        return 'laravel-crud-generator::form.fields.attachments.attachments';
    }


    /**
     * get field handler
     *
     * @return \Dottwatson\CrudGenerator\Crud\Handlers\AttachmentHandler
     */
    protected function getFieldHandler()
    {
        $form  = $this->getParent();
        $model = $form->getModel();

        $attachments = $model->getAvailableAttachements();
        foreach($attachments as $attachment=>$configAttachment){
            if($attachment == $this->name){
                $handlerInfo = $configAttachment['handler'] ?? \Dottwatson\CrudGenerator\Crud\Handlers\AttachmentHandler::class;
                $through = $configAttachment['through']     ?? $this->name;

                if(is_array($handlerInfo)){
                    $handler = new \Dottwatson\CrudGenerator\Crud\Handlers\AttachmentHandler;
                    $handler->setUSerConfig($handlerInfo);
                    $handler->setThrough($through);
                }
                elseif(is_object($handlerInfo)){
                    $handler = $handlerInfo;
                    $handler->init();
                    $handler->setThrough($through);
                }
                else{
                    $handler = new $handlerInfo;
                    $handler->init();
                    $handler->setThrough($through);
                }
            
                return $handler;
            }
        }
    }

    public function getTokenAttachment()
    {
        $form           = $this->getParent();
        $model          = $form->getModel();
        $handler        = $this->getFieldHandler();
        $config         = $handler->getConfig();

        if(!$handler->getConfig('type')){
            $config['type'] = $this->name;
        }

        return encrypt([
            'config'  => $config,
            'name'    => $this->name,
            'owner'   => get_class($model),
            'owner_id'=> $model->getKey()
        ],true);
    }

    public function render(array $options = [], $showLabel = true, $showField = true, $showError = true)
    {
        
        // dd($this->getTokenAttachment());

        $name       = $this->name;
        $form       = $this->getParent();
        $model      = $form->getModel();
        $handler    = $this->getFieldHandler();
        $config     = $handler->getConfig();
        $type       = $config['type'] ?? $name;

        $options['prepend'] = '<i class="fa fa-files"></i>';

        $through = $handler->getThrough();

        $dropzoneSettings                               = $config['uploader'] ?? [];
        $attachments                                    = $model->attachments;
        $dropzoneSettings['headers']['X-CSRF-TOKEN']    = csrf_token();

        $this->prepareOptions($options);

        $data = $this->getRenderData();

        $extrafieldsForm = null;
        if($handler->getConfig('crud.form')){
            $formName           = $handler->getConfig('crud.form');
            $extrafieldsForm    = $form->getFormBuilder()->create($formName,[]);
        }

        // $extrafieldsModelForm = $form->getFormBuilder()->create(AttachmentExtraFieldsForm::class,[]);
        return $this->formHelper->getView()->make(
            $this->getViewTemplate(),
            $data + [
                'uniqId'                => uniqid('upd'),
                'form'                  => $form,
                'owner'                 => $model,
                'owner_id'              => $model->getKey(),
                'name'                  => $name,
                'type'                  => $type,
                'handler'               => $handler,
                'dropzoneSettings'      => $dropzoneSettings,
                'attachments'           => $attachments,
                'options'               => $this->options,
                'instance'              => $this,
                'extrafieldsForm'       => $extrafieldsForm,
                'uploadUrl'             => $this->buildConfigUrl($handler->getConfig('upload')),
                'downloadUrl'           => $this->buildConfigUrl($handler->getConfig('download')),
                'streamUrl'             => $this->buildConfigUrl($handler->getConfig('stream')),
                '_tokenAttachment'      => $this->getTokenAttachment(),
            ]
        )->render();

    }


    public function getTokenFile(Attachment $attachment)
    {
        $form       = $this->getParent();
        $model      = $form->getModel();

        return encrypt([
            'owner'     => get_class($model),
            'owner_id'  => $model->getkey(),
            'model'     => $attachment->model,
            'key'       => md5("{$attachment->getKey()}{$attachment->reference}{$attachment->type}")
        ]);
    }

    protected function buildConfigUrl(array $itemInfo,array $params = [])
    {
        $url = null;
        if($itemInfo['url']){
            $queryStr   = parse_url($itemInfo['url'],PHP_URL_QUERY);

            if($queryStr && $queryStr != ''){
                parse_str($queryStr,$queryData);
            }
            else{
                $queryData = [];
            }

            $queryData = array_replace_recursive($queryData,$params);

            if($queryStr){
                $url = preg_replace('#\?.*$#Usmi','',$itemInfo['url']);
            }
            else{
                $url = $itemInfo['url'];
            }

            $url = ($queryData)
                ?$url.'?'.http_build_query($queryData)
                :$url;
        }
        elseif($itemInfo['route']){
            if(is_array($itemInfo['route'])){
                $routeName                      = $itemInfo['route'][0];
                $routeData                      = $itemInfo['route'][1] ?? [];
                
                $url = route($routeName,$routeData);
            }
            else{
                $url = route($itemInfo['route'],$params);
            }
        }

        return $url;
    }

}
