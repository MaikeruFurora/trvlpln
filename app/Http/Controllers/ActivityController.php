<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityList;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(){
        $lists = ActivityList::getActive()->get(['id','name','color']);
        $sttus = ['success','failed'];
        return view('calendar.index',compact('lists','sttus'));
    }

    public function store(Request $request){
        $time =   Carbon::createFromFormat('h:iA', $request->time_to)->format('H:i');
        $dateFrom = Carbon::parse($request->date_from);
        $timeFrom = $dateFrom->format('H:i');
        if ($timeFrom > $time) {
            return response()->json(['msg'=>'Please check your time.', 'icon'  => 'warning'],500);
        }
       
        if($request->has('week')){
            $startOfWeek = $dateFrom->startOfWeek();
            $dayOfWeek = ($dateFrom->dayOfWeek + 6) % 7;
            for ($i = $dayOfWeek; $i < 6; $i++) {
                $dates = Carbon::parse($startOfWeek->copy()->addDays($i)->toDateString().$time);
                if (!$dates->isPast()) {
                        $startDate = $startOfWeek->copy()->addDays($i)->toDateString();
                        $startTime = $timeFrom;
                        $endTime = $time;
                        $startDateTime = Carbon::parse($startDate . ' ' . $startTime);
                        $endDateTime = Carbon::parse($startDate . ' ' . $endTime);

                        if ($this->checkForOverlappingSchedule($startDateTime, $endDateTime)) {
                            $this->createActivity($request, $startDateTime, $endDateTime);
                        } else {
                            //  response()->json(['msg'=>'Time slot is overlapping with existing activity.', 'icon' => 'warning'], 500);
                        }
                }
            }
            } else {
                if (!$dateFrom->isPast()) {
                    if ($this->checkForOverlappingSchedule($request->date_from,$request->date_from)) {
                        $this->createActivity($request, $dateFrom, $dateFrom->format('Y-m-d').' '.$time);
                    }else{
                        return response()->json(['msg'=>'Please check your CALENDAR for conflicts. Please check your date & time.', 'icon'  => 'warning'],500);
                    }
                } else {
                     return response()->json(['msg'=>'Your date is in the past', 'icon'  => 'warning'],500);
                }
            }
            
            return response()->json(['msg'=>'Your details have been saved', 'icon'  => 'success']);
        

    }

    public function createActivity($request, $dateFrom, $dateTo){
        return auth()->user()->activities()->create([
            'activity_list_id'  => $request->activity,
            'client'            => strtoupper($request->client),
            'note'              => $request->note,
            'sttus'             => $request->sttus,
            'date_from'         => $dateFrom,
            'date_to'           => $dateTo
        ]);
    }


    public function list( $user){
       
        if ($user!="all") {
            $data = User::find($user);
            $activities = $data->load(['activities','activities.activity_list:id,name,color']);
            return $this->renderActivity($activities->activities);
        }else{
             $data = Activity::with(['activity_list:id,name,color'])->get();
            return $this->renderActivity($data);
        }
        
       
    }


    public function renderActivity($data){
        $events = array();
        foreach ($data as $key => $value) {
            $events[] = [
                'id'              =>  $value->id,
                'title'           =>  $value->activity_list->name. !empty($value->client)? $value->client: '',
                'start'           =>  $value->date_from,
                'end'             =>  $value->date_to,
                'osnum'           =>  $value->osnum,
                'note'            =>  $value->note,
                'sttus'           =>  $value->sttus,
                'color'           =>  $this->color($value->sttus),#$value->activity_list->color,
                'textColor'       => 'black',
                'borderColor'       => 'white',
            ];
        }

        return $events;
    }

    public function color($sttus){
        switch ($sttus) {
            case 'success':
                    return '#46cd93';
                break;
            case 'resched':
                    return '#fdba45';
                break;
            case 'failed':
                    return '#dc3545';
                break;
            default:
                    return '#4bbbce';
                break;
        }
    }

    public function update(Activity $activity,Request $request){

        $date = Carbon::parse($activity->date_from);
        if(!$activity) {
            return response()->json([
                'msg' => 'Unable to locate the event',
                'icon'  => 'warning'
            ], 404);
        }
        if (!$date->isPast()) {
                $activity->update([
                    'date_from' => $request->date_from,
                    'date_to'   => $request->date_to,
                    'sttus'     => 'resched',
                ]);
                // Store history of user that triggered the event and the date changes
                $historyData = [
                    'user_id'            => auth()->id(),
                    'event'              => 'update',
                    'original_date_from' => $activity->date_from,
                    'original_date_to'   => $activity->date_to,
                    'new_date_from'      => $request->date_from,
                    'new_date_to'        => $request->date_to,
                ];
                $activity->histories()->create($historyData);
        }else{
            return response()->json([
                'msg'   => 'Not allowed activity',
                'icon'  => 'warning'
            ], 500);
        }
        return response()->json([
            'msg' => 'Updated Activity',
            'icon'  => 'success'
        ], 200);

    }

    public function checkForOverlappingSchedule($start, $end){
        $startTime = Carbon::parse($start);
        $endTime = Carbon::parse($end);
    
        $overlappingActivitiesExist = auth()->user()->activities()
            // ->where(function ($query) use ($startTime, $endTime) {
            //     $query->whereBetween('date_from', [$startTime, $endTime])
            //           ->orWhereBetween('date_to', [$startTime, $endTime]);
            // })
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    $q->where('date_from', '<', $endTime)
                        ->where('date_to', '>', $startTime);
                })
                ->orWhere(function ($q) use ($startTime, $endTime) {
                    $q->where('date_from', '>=', $startTime)
                        ->where('date_to', '<=', $endTime);
                });
            })
            ->exists();
    
        return !$overlappingActivitiesExist;
    }




    public function updateInfo(Activity $activity, Request $request){
        $date_from = Carbon::parse($activity->date_from)->format("Y-m-d");
        if ($date_from==Carbon::now()->format("Y-m-d")) {
            $data =  $activity->update([
                'activity_list_id'  => $request->activity,
                'client'            => $request->client,
                'note'              => $request->note,
                'osnum'             => $request->osnum,
                'sttus'             => $request->sttus[0] ?? null,
            ]);

            if($data){
                return response()->json([
                    'msg' => 'Updated Activity',
                    'icon'  => 'success'
                ], 200);
            }
            
        }else{
            $activity->update([
                'date_from' => $request->date_from,
                'date_to'   => $request->date_to,
            ]);

            return response()->json([
                'msg' => 'Updated Activity',
                'icon'  => 'success'
            ], 200);
            // return response()->json([
            //     'msg'   => 'Not allowed activity',
            //     'icon'  => 'warning'
            // ], 500);
        }
       
    }

    public function updateDate(Activity $activity, Request $request){
        $date = Carbon::parse($activity->date_from);
        if (!$date->isPast()) {
            $activity->update([
                'date_from' => $request->date_from,
                'date_to'   => $request->date_to,
            ]);
            return response()->json([
                'msg' => 'Updated Activity',
                'icon'  => 'success'
            ], 200);
        }else{
            return response()->json([
                'msg'   => 'Not allowed activity',
                'icon'  => 'warning'
            ], 500);
        }
    }

    public function info(Activity $activity){
        return $activity->load(['activity_list:id,name,color']);
    }

    public function destroy(Activity $activity){
        $date = Carbon::parse($activity->date_from);
        if (!$date->isPast()) {
            if($activity->delete()){
                return response()->json([
                    'msg' => 'Deleted Activity',
                    'icon'  => 'success'
                ], 200);
            }
        }else{
            return response()->json([
                'msg'   => 'Not allowed to delete this activity',
                'icon'  => 'warning'
            ], 500);
        }
    }
}
