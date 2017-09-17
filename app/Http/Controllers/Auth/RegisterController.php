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
     * register user via normal form.
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
     * verify registration confirmation.
     * @param  string $verification_code
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
