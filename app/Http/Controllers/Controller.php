<?php

namespace Cemal\Http\Controllers;

use Cemal\Exceptions\FormException;
use Cemal\Exceptions\NotFoundException;
use Cemal\Exceptions\NotValidException;
use Laravel\Lumen\Routing\Controller as BaseController;

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
        } else {
            \Log::critical($e->getMessage());

            return $this->response(null, 500);
        }
    }
}
