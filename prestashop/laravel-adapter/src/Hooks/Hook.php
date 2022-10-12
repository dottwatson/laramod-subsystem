<?php
namespace PrestashopLaravelAdpter\Hooks;

use Illuminate\Http\Request;

class Hook{

    /**
     * data passed from hook
     *
     * @var mixed
     */
    protected $data;

    /**
     * The hook name that instantiate the class
     *
     * @var string
     */
    protected $hookName;

    /**
     * The original request performed
     *
     * @var Illuminate\Http\Request
     */
    protected $request;


    public function __construct(Request $request,$hookName)
    {
        $this->hookName = $hookName;
        $this->request  = $request;
    
        $this->data     = json_decode($request->input('data'),true);
    }

    /**
     * the hook data
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * the hook name
     *
     * @return string
     */
    public function getHookName()
    {
        return $this->name;
    }

    /**
     * The request
     *
     * @return Illuminate\Http\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Handler
     *
     * @return mixed
     */
    public function handle()
    {

    }
}