<?php

namespace App\Http\Controllers;

use App\Models\HandleGroup;
use App\Models\User;
use App\Services\HandleGroupService;
use Illuminate\Http\Request;

class HandleGroupController extends Controller
{

    protected $handleGroupService;

    public function __construct(HandleGroupService $handleGroupService)
    {
        $this->handleGroupService = $handleGroupService;
    }

    public function index()
    {
        $users = User::getActive()->where('type','admin')->get(['id','name']);
        return view('admin.handle-group.index',compact('users'));
    }

    public function store(Request $request){
      
        $data = (empty($request->id)) 

        ? HandleGroup::storeGroup($request)

        : HandleGroup::updateGroup($request);

        if ($data) {
            return response()->json(['msg'=>'Successfully save data']);
        }
    }

    public function list(Request $request){

        return $this->handleGroupService->list($request);

    }


}
