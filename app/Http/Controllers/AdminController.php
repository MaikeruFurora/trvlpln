<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityList;
use App\Models\User;
use App\Models\Wrhs;
use App\Services\AuditService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    public function dashboard(){
     $data = Activity::with(['user:id,name,type,wrhs', 'activity_list:id,name,color,icon'])
    // ->whereBetween('date_from', ['2024-03-02 8:00:00', '2024-03-02 18:00:00'])
    ->whereBetween('date_from', [date("Y-m-d").' 8:00:00', date("Y-m-d").' 18:00:00'])
    ->get()
    ->groupBy(['user.wrhs', 'user.name'])
    ->map(function ($warehouse) {
        return $warehouse->map(function ($userActivities) {
            return [
                'user' => $userActivities->first()->user,
                'activity_count' => $userActivities->count(),
                'failed_count' => $userActivities->where('sttus', 'failed')->count(),
                'success_count' => $userActivities->where('sttus', 'success')->count(),
                'nostatus_count' => $userActivities->whereNull('sttus')->count()+$userActivities->where('sttus', 'resched')->count(),
            ];
        });
    })
    ->sortBy(function ($userActivities, $warehouse) {
        return $warehouse; // Assuming 'wrhs' is directly available as the key after groupBy
    });
       
        return view('admin.dashboard.index',compact('data'));
    }

    public function index(){
        $users = $this->listBdo();
        $lists = ActivityList::getActive()->get(['id','name','color','icon']);
        $wrhs = Wrhs::active()->get(['id','name']);
        return view('admin.index',compact('users','lists','wrhs'));
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

    public function report(Request $request){
            $start = $request->start;
            $end   = $request->end;
            $wrhs  = $request->wrhs;

            if ($wrhs == 'all') {
                $activities = Activity::with('user')
                ->whereBetween('date_from', [ $start.' 8:00:00', $end.' 18:00:00'])
                    ->get();
    
                // Group activities by user and then by activity
                  $reportData = $activities->groupBy('user.name')
                    ->map(function ($activitiesByUser) {
                        return $activitiesByUser->groupBy('activity_list.name')
                            ->map(function ($activitiesByActivityList) {
                                return [
                                    'activity_count' => $activitiesByActivityList->count(),
                                    'activity'        => $activitiesByActivityList,
                                ];
                            });
                    }) ->sortBy(function ($userActivities, $warehouse) {
                        return $warehouse; // Assuming 'wrhs' is directly available as the key after groupBy
                    });
            }else{
               $reportData = User::with(['activities' => function($query) use ($start, $end) {
                    $query->whereBetween('date_from', [$start.' 8:00:00', $end.' 18:00:00']);
                }, 'activities.activity_list'])
                ->where('wrhs', $wrhs)
                ->get();
               
            }
          
            return view('admin.report-template.date-range', compact('reportData','start','end','wrhs'));

    }
}
