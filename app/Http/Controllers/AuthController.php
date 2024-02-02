<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use App\Services\AuthService;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {

        return $this->authService = $authService;

    }

    public function loginPost(LoginRequest $request){

        return $this->authService->login($request);

    }

    public function signOut(){

        return $this->authService->signOut();
        
    }

    

}
