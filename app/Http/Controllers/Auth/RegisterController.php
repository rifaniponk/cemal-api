<?php

namespace Cemal\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Cemal\Services\AuthService;
use Cemal\Services\UserService;
use Cemal\Http\Controllers\Controller;

class RegisterController extends Controller
{
    private $userService;
    private $authService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        UserService $userService,
        AuthService $authService
    ) {
        $this->userService = $userService;
        $this->authService = $authService;
    }

    /**
     * @SWG\Post(
     *     path="/register",
     *     summary="register user",
     *     @SWG\Parameter( name="body", in="body", required=true, @SWG\Schema(ref="#/definitions/NewUser") ),
     *     @SWG\Response(response="201", description="created"),
     *     @SWG\Response(response="400", description="bad input")
     * )
     */
    public function index(Request $request)
    {
        try {
            $data = $request->all();
            $data['verification_code'] = substr(md5(date('Y-m-d H:i:s')), 0, 20);
            $data['register_ip'] = $request->ip();

            $user = $this->userService->create($data);

            return $this->response($user, 201);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }

    /**
     * @SWG\Get(
     *     path="/register/{verification_code}",
     *     summary="confirm registration",
     *     @SWG\Parameter( name="verification_code", in="path", type="string"),
     *     @SWG\Response(response="200", description="OK"),
     *     @SWG\Response(response="404", description="not found")
     * )
     */
    public function verify($verification_code)
    {
        try {
            $this->authService->verifyRegistration($verification_code);

            return $this->response(null, 200);
        } catch (\Exception $e) {
            return $this->handleError($e);
        }
    }
}
