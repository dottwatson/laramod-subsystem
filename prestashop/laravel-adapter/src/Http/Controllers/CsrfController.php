<?php

namespace PrestashopLaravelAdpter\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CsrfController extends Controller
{
    
    /**
     * returns a fresh token
     *
     * @return string
     */
    public function get()
    {
        echo csrf_token();
        die();
    }
    
    /**
     * returns a token field to use in forms
     *
     * @return string
     */
    public function getField()
    {
        return csrf_field();
    }
}
