<?php

namespace Cemal\Http\Controllers\Auth;

use Cemal\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Cemal\Services\UserService;
use Cemal\Services\AuthService;

class PasswordController extends Controller
{
    private $authService;

    public function __construct(
        AuthService $authService
    ){
        $this->authService = $authService;
    }

    public function reset(Request $request)
    {
    	# code...
    }

    public function requestReset(Request $request)
    {
    	try {
            $this->authService->requestReset($request->all());
            return $this->response(null, 200, 'Email untuk perubahan password telah dikirim');
        } catch(\Exception $e) {
            return $this->handleError($e);
        }
    }
}