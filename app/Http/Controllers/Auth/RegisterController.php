<?php

namespace Cemal\Http\Controllers\Auth;

use Cemal\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Cemal\Services\UserService;

class RegisterController extends Controller
{
    private $userService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * register user via normal form
     */
    public function index(Request $request){
        try {
            $data = $request->all();
            $data['verification_code'] = substr(md5(Date('Y-m-d H:i:s')),0,20);
            
            $user = $this->userService->create($data);
            return $this->response($user, 201);
        } catch(\Exception $e) {
            return $this->handleError($e);
        }
    }
}
