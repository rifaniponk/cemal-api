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
            $user = $this->userService->create($request->all());
            return response()->json($user, 201);
        } catch(\Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }
}
