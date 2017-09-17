<?php

namespace Cemal\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Cemal\Services\AuthService;
use Cemal\Http\Controllers\Controller;

class PasswordController extends Controller
{
    private $authService;

    public function __construct(
        AuthService $authService
    ) {
        $this->authService = $authService;
    }

    public function reset(Request $request)
    {
        try {
            $this->authService->resetPassword($request->all());

            return $this->response(null, 200, 'Password telah berhasil diubah');
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    public function requestReset(Request $request)
    {
        try {
            $this->authService->requestReset($request->all());

            return $this->response(null, 200, 'Email untuk perubahan password telah dikirim');
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }
}
