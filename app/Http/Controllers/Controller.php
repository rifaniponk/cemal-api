<?php

namespace Cemal\Http\Controllers;

use Cemal\Exceptions\FormException;
use Cemal\Exceptions\NotFoundException;
use Cemal\Exceptions\NotValidException;
use Cemal\Exceptions\AccessDeniedException;
use Laravel\Lumen\Routing\Controller as BaseController;

/**
 * @SWG\Swagger(
 *   schemes={"http"},
 *   basePath="/v1",
 *   info={
 *     "title"="Cemal API Documentation",
 *     "version"="1.0.0"
 *   }
 * )
 */
class Controller extends BaseController
{
    /**
     * wrap data into json response.
     * @param  array | Object 	$data
     * @param  int 				$status
     */
    protected function response($data, $status, $message = null)
    {
        if (! $message) {
            if ($status === 201) {
                $message = 'Created';
            } elseif ($status >= 200 && $status < 300) {
                $message = 'OK';
            } elseif ($status === 400) {
                $message = 'Bad input';
            } elseif ($status === 401) {
                $message = 'Unauthorized';
            } elseif ($status === 403) {
                $message = 'Forbidden';
            } elseif ($status === 404) {
                $message = 'Not found';
            } elseif ($status === 500) {
                $message = 'Internal Server Error';
            }
        }
        $response = [
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ];

        return response()->json($response, $status);
    }

    /**
     * Handle exception.
     * @param  \Exception $e
     */
    protected function handleError(\Exception $e)
    {
        if (get_class($e) === FormException::class) {
            return $this->response($e->getMessages(), 400);
        } elseif (get_class($e) === NotFoundException::class) {
            return $this->response(null, 404, $e->getMessage());
        } elseif (get_class($e) === NotValidException::class) {
            return $this->response(null, 400, $e->getMessage());
        } elseif (get_class($e) === AccessDeniedException::class) {
            return $this->response(null, 403, $e->getMessage());
        } else {
            \Log::critical(get_class($e).': '.$e->getMessage());

            return $this->response(null, 500);
        }
    }

    /**
     * validate user's privilege
     * @param  string $right  
     * @param  string | object $object 
     * @return true if user is granted
     */
    protected function validatePrivilege($right, $object)
    {
        $granted = \Gate::allows('view', \Auth::user());
        if (!$granted){
            throw new AccessDeniedException();
        }
        return true;
    }
}
