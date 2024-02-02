<?php

namespace App\Http\Controllers;

use App\Models\ActivityList;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\ActivityListService;

class ActivityListController extends Controller
{
    protected $activityListService;

    public function __construct(ActivityListService $activityListService)
    {
        $this->activityListService = $activityListService;
    }

    public function index(){
        
        return view('admin.list.index');
    }

    public function store(Request $request){
      
        $data = (empty($request->id)) 

        ? ActivityList::storeList($request)

        : ActivityList::updateList($request);

        if ($data) {
            return response()->json(['msg'=>'Successfully save data']);
        }
    }

    public function list(Request $request){

        return $this->activityListService->list($request);

    }

   
}
