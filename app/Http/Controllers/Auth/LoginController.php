<?php

namespace Cemal\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Cemal\Services\AuthService;
use Cemal\Http\Controllers\Controller;

class LoginController extends Controller
{
    private $authService;

    public function __construct(
        AuthService $authService
    ) {
        $this->authService = $authService;
    }

    /**
     * @SWG\Post(
     *     path="/login",
     *     summary="login",
     *     @SWG\Parameter( name="body", in="body", required=true, @SWG\Schema(ref="#/definitions/Login") ),
     *     @SWG\Response(response="200", description="ok"),
     *     @SWG\Response(response="400", description="wrong email/password")
     * )
     */
    public function login(Request $request)
    {
        try {

            $userToken = $this->authService->login($request->all(), $this->getAuthData($request));

            return $this->response($userToken, 200);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    /**
     * @SWG\Post(
     *     path="/logout",
     *     summary="logout",
     *     @SWG\Response(response="200", description="ok"),
     *     @SWG\Response(response="401", description="unauthenticated")
     * )
     */
    public function logout(Request $request)
    {
        try {

            $this->authService->logout($this->getAuthData($request));

            return $this->response(null, 200);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    private function getAuthData(Request $request)
    {
        return [
                'ip' => $request->ip(),
                'browser' => substr($request->userAgent(), 0, 50),
            ];
    }
}
