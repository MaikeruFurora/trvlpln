<?php

namespace App\Http\Controllers;

use App\Models\ActivityList;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    public function index(){
        $users = $this->listBdo();
        $lists = ActivityList::getActive()->get(['id','name','color','icon']);
        return view('admin.index',compact('users','lists'));
    }

    public function listBdo(){
        $tmp = [];
        foreach(User::notAdmin()->get(['id','name','type','wrhs']) as $key => $arg){
            $tmp[$arg->wrhs][] = $arg;
        }
       return $tmp;
    }

    public function audit(){
        return view('admin.audit.index');
    }

    public function auditList(Request $request){
        return $this->auditService->list($request);
    }
}
