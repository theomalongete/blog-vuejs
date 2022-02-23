<?php

namespace App\Traits;

use App\Exceptions\RequestValidationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Traits are a mechanism for code reuse in single inheritance languages such 
 * as PHP.  A Trait is intended to reduce some limitations of single 
 * inheritance by enabling a developer to reuse sets of methods freely in 
 * several independent classes living in different class hierarchies.
 */

trait APIResponder{

    /**
     * Return success response
     *
     * @param string $message
     * @param integer $code
     * @return void
     */
    protected function successResponse($message = null, $code = 200){
        return response()->json(['result'=>'success','message'=>$message,'code'=>$code],$code);
    }
    
    /**
     * Return success processed response
     *
     * @param integer $recordCount
     * @param string $message
     * @param integer $code
     * @return void
     */
    protected function successProcessedResponse($recordCount = 0, $message = null, $code = 200){
        return response()->json(['result'=>'success','message'=>$message,'code'=>$code,'recordCount'=>$recordCount],$code);
    }
    
    /**
     * Return error response
     *
     * @param string $message
     * @param integer $code
     * @return void
     */
    protected function errorResponse($message = null, $code = null){
        return response()->json(['result'=>'error','message'=>$message,'code'=>$code],$code);
    }

    /**
     * Return error processed response
     *
     * @param integer $recordCount
     * @param string $message
     * @param integer $code
     * @return void
     */
    protected function errorProcessedResponse($recordCount = 0, $message = null, $code = 200){
        return response()->json(['result'=>'error','message'=>$message,'code'=>$code,'recordCount'=>$recordCount],$code);
    }
}