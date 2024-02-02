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


        $dateFrom = Carbon::parse($request->date_from);
        if ($this->checkForOverlappingSchedule($request->date_from,$request->date_from,1)) {
            if($request->has('week')){
                $startOfWeek = $dateFrom->startOfWeek();
                $dateTime = Carbon::parse($request->date_from);
                $dayOfWeek = ($dateFrom->dayOfWeek + 6) % 7;
                
                for ($i = $dayOfWeek; $i < 6; $i++) {
                    $dates = Carbon::parse($startOfWeek->copy()->addDays($i)->toDateString().' '.$dateTime->toTimeString());
                    
                    if (!$dates->isPast()) {
                        $this->createActivity($request, $startOfWeek->copy()->addDays($i), $dateTime);
                    }
                }
            } else {
                if (!$dateFrom->isPast()) {
    
                    $this->createActivity($request, $dateFrom, Carbon::parse($request->date_from));
                } else {
                     return response()->json(['msg'=>'Please check your date & time.', 'icon'  => 'warning'],500);
                }
            }
            
            return response()->json(['msg'=>'Your details have been saved', 'icon'  => 'success']);
        }else{
            return response()->json(['msg'=>'Please check your date & time.', 'icon'  => 'warning'],500);
        }

    }

    public function createActivity($request, $dateFrom, $dateTime){
        return auth()->user()->activities()->create([
            'activity_list_id'  => $request->activity,
            'client'            => strtoupper($request->client),
            'note'              => $request->note,
            'sttus'             => $request->sttus,
            'date_from'         => $dateFrom->toDateString().' '.$dateTime->toTimeString(),
            'date_to'           => $dateFrom->addMinute(59)
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
                'borderColor'     => 'white',
                'color'           =>  $this->color($value->sttus),#$value->activity_list->color,
                'textColor'       => 'black',
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

    public function update(Activity $activity,Request $request)
    {
        $date = Carbon::parse($activity->date_from);
        if(!$activity) {
            return response()->json([
                'msg' => 'Unable to locate the event',
                'icon'  => 'warning'
            ], 404);
        }
        if (!$date->isPast()) {
           if ($this->checkForOverlappingSchedule($request->date_from,$request->date_to)) {
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
                return response()->json(['msg'=>'We encounter error && Check your date & time', 'icon'  => 'warning'],500);
            }
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

    public function checkForOverlappingSchedule($start, $end,$addHours)
    {
        $startTime = Carbon::parse($start);
        $endTime = Carbon::parse($end)->addMinute(59);//->addHours(1);
    
        $overlappingActivitiesExist = auth()->user()->activities()
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('date_from', [$startTime, $endTime])
                      ->orWhereBetween('date_to', [$startTime, $endTime]);
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
