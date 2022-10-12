<?php 
namespace Dottwatson\CrudGenerator\Crud\Handlers;

use Illuminate\Support\Arr;

/**
 * This is the attachment handler used to manage attachments crud informations
 */
class AttachmentHandler{
    
    protected $through       = '';
    protected $userConfig    = [];
    protected $fullConfig    = [];
    protected $defaultConfig = [
        'limit'     => 1000000,
        'model'     =>\Dottwatson\CrudGenerator\Models\Attachment::class,
        'reference' => '{id}',
        'crud'      => [
            'attachment'    => null,
            'field'         => \Dottwatson\CrudGenerator\Form\Fields\Attachments::class, //the form field
            'sheet'         => null, //the sheet field
        ],
        'uploader' => [
            'acceptedFiles' => '',
            'chunking'      => true,
            'maxFilesize'   => 400000000,
            'chunkSize'     => 1000000,
        ],
        'storage'   => [
            'temp_path' => 'tmp', // the default temporary path where file is placed before is moved,
            'disk'      => 'local', //the disk (see filesystems)
            'path'      => '{id}/{attachment.type}', //the final path where file will be moved when relation will be made
        ],
        'upload' => [
            'route' => null,
            'url'   => null,
        ],
        'stream' => [
            'route' => null,
            'url'   => null,
        ],
        'download' => [
            'route' => null,
            'url'   => null,
        ],
    ];


    public function __construct()
    {
        $this->init();

        $this->makeConfig();
    }

    /**
     * get the relation hasManyThrough method name
     *
     * @return string|null
     */
    public function getThrough()
    {
        return $this->through;
    }


    /**
     * set the relation hasManyThrough method name
     *
     * @return static
     */
    public function setThrough(string $through)
    {
        $this->through = $through;

        return $this;
    }

    /**
     * initialize the object, used also tu set the configuration
     *
     * @return void
     */
    public function init()
    {
        //put here your logic

        // here also will be set the configuration from user or extended class
        // if not called will be fired itself only on the first class call
        // for set the configuration is useful use
        // $this->setUserConfig(myArrayOfParameters);
    }

    /**
     * initialize the object, composing the fullConfig
     *
     * @return void
     */
    protected function makeConfig()
    {
        $this->fullConfig = array_replace_recursive(
            $this->defaultConfig,
            $this->userConfig
        );

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
            return $this->fullConfig;
        }
        else{
            $value = Arr::get($this->fullConfig,$key,$default);
            return ($value === null)?$default:$value;
        }
    }

    /**
     * set configuration 
     *
     * @param array $config
     * @return void
     */
    public function setUserConfig(array $config = [])
    {
        $this->userConfig = $config;
        $this->makeConfig();

        return $this;
    }


    /**
     * returns the primitive configuration imputed by user definition
     *
     * @return array
     */
    public function getUserConfig()
    {
        return $this->userConfig;
    }

    /**
     * retuns the default configuration
     *
     * @return array
     */
    public function getDefaultConfig()
    {
        return $this->defaultConfig;
    }
}