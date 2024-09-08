<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Services\GroupService;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    protected $groupService;

    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }
    public function index(){
        
        return view('admin.group.index');
    }

    public function store(Request $request){
      
        $data = (empty($request->id)) 

        ? Group::storeGroup($request)

        : Group::updateGroup($request);

        if ($data) {
            return response()->json(['msg'=>'Successfully save data']);
        }
    }

    public function list(Request $request){

        return $this->groupService->list($request);

    }
}
