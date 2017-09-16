<?php

namespace Cemal\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Cemal\Exceptions\FormException;

class Controller extends BaseController
{	
	/**
	 * wrap data into json response
	 * @param  array | Object 	$data  
	 * @param  int 				$status
	 */
	protected function response($data, $status){
		$response = [
			'status' => $status,
			'data' => $data
		];

		return response()->json($response, $status);
	}

	/**
	 * Handle exception
	 * @param  \Exception $e
	 */
    protected function handleError(\Exception $e){
    	if (get_class($e) === FormException::class){
    		return $this->response($e->getMessages(), 400);
    	} else {
	    	\Log::critical($e->getMessage());
	        return $this->response('Internal Server Error', 500);
    	}
    }
}
