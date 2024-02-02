<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuthService;

use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function index(){
        $wrhs = ['Bacolod','Cagayan','Cebu','Davao','Iloilo','Pampanga','Tabaco','Surigao','Zamboanga','Main'];
        return view('admin.user.index',compact('wrhs'));
    }

    public function Apilist(){
        return User::typeBDO()->get(['id','name','wrhs']);
    }

    public function list(Request $request){
        return $this->authService->list($request);
    }

    public function store(Request $request){
       
        $data = (empty($request->id)) 

        ? User::storeUser($request)

        : User::updateUser($request);

        if ($data) {
            return response()->json(['msg'=>'Successfully save data']);
        }
    }
    
    public function signOut(){
        
        return $this->authService->signOut();

    }
}
