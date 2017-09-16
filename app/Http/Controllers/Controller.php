<?php

namespace Cemal\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Cemal\Exceptions\FormException;
use Cemal\Exceptions\NotFoundException;
use Cemal\Exceptions\NotValidException;

class Controller extends BaseController
{	
	/**
	 * wrap data into json response
	 * @param  array | Object 	$data  
	 * @param  int 				$status
	 */
	protected function response($data, $status, $message = null){
		if (!$message){
			if ($status === 201){
				$message = 'Created';
			} else if ($status >= 200 && $status < 300 ){
				$message = 'OK';
			} else if ($status === 400 ){
				$message = 'Bad input';
			} else if ($status === 401 ){
				$message = 'Unauthorized';
			} else if ($status === 404 ){
				$message = 'Not found';
			} else if ($status === 500 ){
				$message = 'Internal Server Error';
			}
		}
		$response = [
			'status' => $status,
			'message' => $message,
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
    	} else if (get_class($e) === NotFoundException::class){
    		return $this->response(null, 404, $e->getMessage());
    	} else if (get_class($e) === NotValidException::class){
    		return $this->response(null, 400, $e->getMessage());
    	} else {
	    	\Log::critical($e->getMessage());
	        return $this->response(null, 500);
    	}
    }
}
