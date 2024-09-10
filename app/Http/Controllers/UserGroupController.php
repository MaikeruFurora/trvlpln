<?php

namespace App\Http\Controllers;

use App\Models\HandleGroup;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserGroup;
use App\Services\UserGroupService;

class UserGroupController extends Controller
{

    protected $userGroupService;

    public function __construct(UserGroupService $userGroupService)
    {
        $this->userGroupService = $userGroupService;
    }

    /**
     * Show the handle group index page
     *
     * @return \Illuminate\Http\Response
     */

    public function index(){

        $users = User::getActive()->typeVisor()->typeBDO()->get(['id','name']);
        $handleGroups = HandleGroup::getActive()->get(['id','name']);
        return view('admin.user-group.index',compact('users','handleGroups'));

    }


    public function store(Request $request){
      
        $data = (empty($request->id)) 

        ? UserGroup::storeGroup($request)

        : UserGroup::updateGroup($request);

        if ($data) {
            return response()->json(['msg'=>'Successfully save data']);
        }
    }

    public function list(Request $request){

        return $this->userGroupService->list($request);

    }
}
