<?php

namespace Cemal\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Cemal\Exceptions\FormException;

class Controller extends BaseController
{
	/**
	 * Handle exception
	 * @param  \Exception $e
	 */
    protected function handleError(\Exception $e){
    	if (get_class($e) === FormException::class){
    		return response()->json($e->getMessages(), 400);
    	} else {
	    	\Log::critical($e->getMessage());
	        return response()->json('Internal Server Error', 500);
    	}
    }
}
