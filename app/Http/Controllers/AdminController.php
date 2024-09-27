<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityList;
use App\Models\HandleGroup;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\Wrhs;
use App\Services\AuditService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    protected $auditService;
    protected $monday;
    protected $saturday;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
        $this->monday       = Carbon::now()->startOfWeek()->format('Y-m-d'); 
        $this->saturday     = Carbon::now()->endOfWeek()->format('Y-m-d');
    }

    public function dashboard(){
     $data = Activity::with(['user:id,name,type,wrhs,is_active', 'activity_list:id,name,color,icon'])
        // ->whereBetween('date_from', ['2024-03-02 8:00:00', '2024-03-02 18:00:00'])
        ->whereBetween('date_from', [date("Y-m-d").' 8:00:00', date("Y-m-d").' 18:00:00'])
        ->whereHas('user',function($query){
            return $query->where('users.is_active','YES');
        })
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
      
     $dataWeekTable =  Activity::with(['user:id,name,type,wrhs', 'activity_list:id,name,color,icon'])
                    ->whereBetween('date_from', [$this->monday, $this->saturday])
                    ->whereHas('user',function($query){
                        return $query->where('users.is_active','YES');
                    })
                    ->get()
                    ->groupBy(['user.name'])
                    ->map(function ($userActivities) {
                        return $userActivities->groupBy(function ($activity) {
                            return Carbon::parse($activity->date_from)->format('Y-m-d');
                        })
                            ->map(function ($activitiesByDate) {
                                return [
                                    'date' => Carbon::parse($activitiesByDate->first()->date_from)->format('Y-m-d'),
                                    'count' => $activitiesByDate->count(),
                                    'activities' => $activitiesByDate,
                                ];
                        })
                            ->mapWithKeys(function ($activitiesByDate) {
                                return [
                                    $activitiesByDate['date'] => $activitiesByDate['count'],
                                ];
                            });
                    });

    
                
                  
        return view('admin.dashboard.index',compact('data','dataWeekTable'));
    }

    public function index(){
        $wrhsWithBDO = $this->listBdo();
        $users = User::notAdmin()->getActive()->get(['id','name','type','wrhs']);
        $lists = ActivityList::getActive()->get(['id','name','color','icon']);
        $wrhs = Wrhs::active()->get(['id','name']);
        $groupedArray = $this->userGroup();
        return view('admin.index',compact('users','lists','wrhs','groupedArray','wrhsWithBDO'));
    }

    public function userGroup(){
        $results = UserGroup::select([
            'user_groups.id',
            'handle_groups.name as handle_group_name',
            'handle_groups.user_id as handle_group_user_id',
            'handle_groups.id as handle_group_id',
            'handle_groups.created_at',
            'user_groups.user_id',
            'users.name as user_name'
        ])
        ->join('users', 'user_groups.user_id', 'users.id')
        ->join('handle_groups', 'user_groups.handle_group_id', 'handle_groups.id')
        ->where('users.is_active', 'YES')
        ->where('handle_groups.user_id', auth()->user()->id)
        ->get();
        
        // Group by handle_group_name
        $groupedResults = $results->groupBy('handle_group_name')->map(function ($group) {
            return $group->map(function ($item) {
                return [
                    'id' => $item->id,
                    'user_id' => $item->user_id,
                    'user_name' => $item->user_name,
                    'created_at' => $item->created_at,
                ];
            });
        });
        
        // Convert to array if necessary
        $groupedArray = $groupedResults->toArray();
        
        return $groupedArray;
        
        
    }

    public function listBdo(){
        $tmp = [];
        foreach(User::notAdmin()->getActive()->get(['id','name','type','wrhs']) as $key => $arg){
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
                                    'activity'       => $activitiesByActivityList,
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
                ->getActive()
                ->get();
               
            }
          
            return view('admin.report-template.date-range', compact('reportData','start','end','wrhs'));
    }

    public function reportExcel(Request $request){
        
    }
}
