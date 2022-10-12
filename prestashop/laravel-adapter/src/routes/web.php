<?php
use Illuminate\Support\Facades\Route;
use PrestashopLaravelAdpter\Http\Controllers\CsrfController;
use Illuminate\Http\Request;




Route::group(['middleware' => ['web']], function () {
    Route::any('csrf',[CsrfController::class,'get']);
    Route::any('csrf-field',[CsrfController::class,'getField']);

    //hooks route
    Route::post('hook/{hookName}',function(Request $request,$hookName){
        echo $hookName;die();
        $request->getContent();
        $response = '';            
        if(config('hooks.'.$hookName)){
            $handlers = config('hooks.'.$hookName);
            $handlers = (is_string($handlers))
                ?[$handlers]
                :$handlers; 
        
            foreach($handlers as $handler){
                $hookInstance   = new $handler($request,$hookName);
                $response      .= $hookInstance->handle();
            }
        }

        return response($response);
    });

});
