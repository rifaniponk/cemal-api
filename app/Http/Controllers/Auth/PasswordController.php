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

    /**
     * @SWG\Post(
     *     path="/password/reset",
     *     summary="Reset password",
     *     @SWG\Parameter( name="body", in="body", required=true, @SWG\Schema(ref="#/definitions/ResetPassword") ),
     *     @SWG\Response(response="200", description="ok"),
     *     @SWG\Response(response="400", description="bad input")
     * )
     */
    public function reset(Request $request)
    {
        try {
            $this->authService->resetPassword($request->all());

            return $this->response(null, 200, 'Password telah berhasil diubah');
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    /**
     * @SWG\Post(
     *     path="/password/email",
     *     summary="Request reset password",
     *     @SWG\Parameter( name="body", in="body", required=true, @SWG\Schema(ref="#/definitions/RequestResetPassword") ),
     *     @SWG\Response(response="200", description="ok"),
     *     @SWG\Response(response="404", description="not found")
     * )
     */
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
