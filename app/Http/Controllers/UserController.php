<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use App\Models\Wrhs;
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
        $groups = Group::active()->orderBy('name')->orderBy('name')->get(['id','name']);
        $wrhs = Wrhs::active()->orderBy('name')->orderBy('name')->get(['id','name']);
        return view('admin.user.index',compact('wrhs','groups'));
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
