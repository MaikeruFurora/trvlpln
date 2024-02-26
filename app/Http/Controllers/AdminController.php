<?php

namespace App\Http\Controllers;

use App\Models\Activity;
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

    public function dashboard(){
     $data = Activity::with(['user:id,name,type,wrhs', 'activity_list:id,name,color,icon'])
    ->whereBetween('date_from', ['2024-02-20 8:00:00', '2024-02-20 18:00:00'])
    // ->whereBetween('date_from', [date("Y-m-d").' 8:00:00', date("Y-m-d").' 18:00:00'])
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

    public function report(Request $request){
        // Ensure $request has date range parameters
        // if ($request->has(['start', 'end'])) {
            // Query activities within the specified date range
            $start = $request->start;
            $end   = $request->end;
            $activities = Activity::with('user')
            ->whereBetween('date_from', ['2024-02-20 8:00:00', '2024-02-20 18:00:00'])
                ->get();

            // Group activities by user and then by activity
             $reportData = $activities->groupBy('user.name','user.wrhs')
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

            // Return the view with the reportData
            return view('admin.report-template.date-range', compact('reportData','start','end'));
        // }
        // Return the view without data if date range is not provided
        // return view('admin.report-template.date-range');
    }
}
