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

    public function report(Request $request){
        
        $start = $request->start;
        $end   = $request->end;
        $wrhs  = $request->wrhs;
        $user  = null;
        if ($wrhs == 'all') {
            $reportData = User::with(['activities' => function($query) use ($start, $end) {
                $query->whereBetween('date_from', [$start.' 8:00:00', $end.' 18:00:00']);
            }, 'activities.activity_list'])
            ->where('wrhs', auth()->user()->wrhs)
            ->typeBDO()
            ->get();
        }else{
            
            $user = User::find($wrhs);
            $reportData = $user->activities()
            ->whereBetween('date_from', [$start.' 8:00:00', $end.' 18:00:00'])
            ->with('activity_list:id,name')
            ->get();
        }
      
        return view('supervisor.report-template.date-range', compact('reportData','start','end','wrhs','user'));

    }
}
