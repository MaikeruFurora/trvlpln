<?php

namespace App\Http\Controllers;

use App\Models\ActivityList;
use App\Models\User;
use Illuminate\Http\Request;

class SupervController extends Controller
{
    public function index(){
        $lists = ActivityList::getActive()->get(['id','name','color']);
        $sttus = ['success','failed'];
        $bdo   = User::getMySupervision(auth()->user()->type,auth()->user()->wrhs)->get(['id','name','wrhs']);
        return view('supervisor.index',compact('lists','sttus','bdo'));
    }

    public function list(){
        return;
    }
}
