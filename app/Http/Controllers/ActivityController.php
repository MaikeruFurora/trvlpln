<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityList;
use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{
    public function index(){
        $lists = ActivityList::getActive()->get(['id','name','color','icon']);
        $sttus = ['success','failed'];
        return view('bdo.index',compact('lists','sttus'));
    }

    public function store(Request $request){

        $timeFrom     = $request->time_from;
        $timeTo       = $request->time_to;
        $date         = Carbon::parse($request->date_from);
        $dateTimeFrom = Carbon::parse($request->date_from.' '.$timeFrom);
        $dateTimeTo   = Carbon::parse($request->date_from.' '.$timeTo);


        if($this->checkTime($timeFrom,$timeTo)){
            return response()->json(['msg'=>'Please check your time.', 'icon'  => 'warning'],500);
        }
       
        if($request->has('week')){
            $startOfWeek = $date->startOfWeek();
            $dayOfWeek = ($date->dayOfWeek + 6) % 7;
            for ($i = $dayOfWeek; $i < 6; $i++) {
                $dates = Carbon::parse($startOfWeek->copy()->addDays($i)->toDateString().$timeTo);
                if (!$dates->isPast()) {
                        $startDate = $startOfWeek->copy()->addDays($i)->toDateString();
                        $startTime = $timeFrom;
                        $endTime = $timeTo;
                        $startDateTime = Carbon::parse($startDate . ' ' . $startTime);
                        $endDateTime = Carbon::parse($startDate . ' ' . $endTime);

                        if ($this->checkForOverlappingSchedule($startDateTime, $endDateTime)) {
                            if ($this->allowedTime($timeFrom, $timeTo)) {
                                $this->createActivity($request, $startDateTime, $endDateTime);
                            }
                        } else {
                            //  response()->json(['msg'=>'Time slot is overlapping with existing activity.', 'icon' => 'warning'], 500);
                        }
                }
            }
            } else {
                    $date_from = $date->format("Y-m-d");
                    if ($date_from==Carbon::now()->format("Y-m-d")) {
                        if ($this->allowedTime($timeFrom, $timeTo)) {
                            $this->createActivity($request, $dateTimeFrom, $dateTimeTo);
                        }else{
                            return response()->json(['msg'=>'Please check your time. allowed time 8:00 AM - 8:00 PM', 'icon'  => 'warning'],500);
                        }
                    }else{
                        if (!$date->isPast()) {
                            if ($this->checkForOverlappingSchedule($date->format('Y-m-d').' '.$timeFrom,$date->format('Y-m-d').' '.$timeTo )) {
                                if ($this->allowedTime($timeFrom, $timeTo)) {
                                    if (!$this->isSunday($date_from)) {
                                        $this->createActivity($request, $dateTimeFrom, $dateTimeTo);
                                    }else{
                                        return response()->json(['msg'=>'Date is Sunday', 'icon'  => 'warning'],500);
                                    }
                                }else{
                                    return response()->json(['msg'=>'Please check your time. allowed time 8:00 AM - 8:00 PM', 'icon'  => 'warning'],500);
                                }
                            }else{
                                return response()->json(['msg'=>'Please check your CALENDAR for conflicts. Please check your date & time.', 'icon'  => 'warning'],500);
                            }
                        } else {
                            return response()->json(['msg'=>'The date you chose is no longer valid.', 'icon'  => 'warning'],500);
                        }
                    }
              
            }
            
            return response()->json(['msg'=>'Your details have been saved', 'icon'  => 'success']);
        

    }

    
    public function isSunday($date){
        return (Carbon::parse($date)->dayOfWeek == Carbon::SUNDAY);
    }

    public function allowedTime($timeFrom, $timeTo){
        return ($timeFrom>= '07:00' && $timeTo <= '20:00');
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


    public function list( $user, Request $request){

        if ($user!="all") {
            $data = User::find($user);
            $activities = $data->activities()
                        ->whereBetween('date_from', [$request->start, $request->end])
                        ->with('activity_list:id,name,color,icon')
                        ->get();
            return $this->renderActivity($activities);
        }else{
            // $data = Activity::with(['activity_list:id,name,color,icon'])->whereBetween('date_from', [$request->start, $request->end])->get();
            $data = Activity::with(['activity_list:id,name,color,icon'])
                ->whereBetween('date_from', [$request->start, $request->end])
                ->get();
            return $this->renderActivity($data);
        }
       
    }


    public function renderActivity($data){
        $events = array();
        foreach ($data as $key => $value) {
            $events[] = [
                'id'              =>  $value->id,
                'title'           =>  $value->client,
                'start'           =>  $value->date_from,
                'end'             =>  $value->date_to,
                'osnum'           =>  $value->osnum,
                'note'            =>  $value->note,
                'sttus'           =>  $value->sttus,
                'activity'        =>  $value->activity_list->name,
                'activityColor'   =>  $value->activity_list->color,
                'color'           =>  $this->color($value->sttus),#$value->activity_list->color,
                'icon'            =>  $value->activity_list->icon,
                // 'textColor'       =>  'black',
                // 'borderColor'     =>  'white',
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
            case 'cancelled':
                    return '#bc9090';
                break;
            default:
                    return '#3788d8';
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

    public function isPastDate($date) {
        $givenDate = Carbon::parse($date);
        $currentDate = Carbon::now()->startOfDay(); // Set time to 00:00:00 for comparison
        
        return $givenDate->isBefore($currentDate);
    }

    public function checkTime($timeFrom,$timeTo){
        return ($timeFrom > $timeTo);
    }


    public function updateInfo(Activity $activity, Request $request){
        
        $date_fromDB = Carbon::parse($activity->date_from);
        
        $timeFrom     = $request->time_from;
        $timeTo       = $request->time_to;
        $date         = Carbon::parse($request->date_from);
        $dateTimeFrom = Carbon::parse($request->date_from.' '.$timeFrom);
        $dateTimeTo   = Carbon::parse($request->date_from.' '.$timeTo);
        $booking      = json_decode($request->booking);

        if($request->has('booking')){
            $this->booking($booking,$activity);
        }

        if($this->checkTime($timeFrom,$timeTo)){
            return response()->json(['msg'=>'Please check your time.', 'icon'  => 'warning'],500);
        }

        if($this->isPastDate($request->date_from)){
            return response()->json([
                'msg' => 'The date you chose is no longer valid.',
                'icon'  => 'warning'
            ], 500);
        }

        if ($date->format("Y-m-d")==Carbon::now()->format("Y-m-d")) {

            $data =  $activity->update([
                'date_from'         => $dateTimeFrom->format('Y-m-d H:i:s'),
                'date_to'           => $dateTimeTo->format('Y-m-d H:i:s'),
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
            
            // user can update only if date is not past

            if (!$date_fromDB->isPast()) {
                $activity->update([
                    'date_from' => $dateTimeFrom->format('Y-m-d H:i:s'),
                    'date_to'   => $dateTimeTo->format('Y-m-d H:i:s'),
                ]);
                return response()->json([
                    'msg' => 'Updated Activity Time Slot',
                    'icon'  => 'success'
                ], 200);
            }else{
                return response()->json([
                    'msg'   => 'Not permitted because the date has passed',
                    'icon'  => 'warning'
                ], 500);
            }
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

    public function info(Activity $activity)
    {
        // Load the related data
        $activityDetails = $activity->load([
            'activity_list:id,name,color',
            'bookings:id,activity_id,product_id,free_type,price,qty',
            'bookings.product:id,name'
        ]);
    
        $data = [
            'activity_list_id'    => $activityDetails->activity_list?->id,
            'activity_list_name'  => $activityDetails->activity_list?->name,
            'activity_list_color' => $activityDetails->activity_list?->color,
            'activity_id'         => $activityDetails->id,
            'client'              => $activityDetails->client,
            'date_from'           => $activityDetails->date_from,
            'date_to'             => $activityDetails->date_to,
            'note'                => $activityDetails->note,
            'osnum'               => $activityDetails->osnum,
            'user_id'             => $activityDetails->user_id,
            'id'                  => $activityDetails->id,
            'sttus'               => $activityDetails->sttus,
            'isDelete'            => Carbon::parse($activityDetails->date_from)->isPast(),
            'booking'             => $activityDetails->bookings->map(function ($booking) {
                return [
                    'id'           => $booking->id,
                    'product_id'   => $booking->product?->id,
                    'name'         => $booking->product?->name ?? $booking->free_type,
                    'free_type'    => $booking->free_type ??  $booking->product?->name ,
                    'price'        => $booking->price,
                    'qty'          => $booking->qty,
                ];
            })->toArray()
        ];
    
        return response()->json($data);
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

    public function destroyBooking(Booking $booking){
        $date = Carbon::parse($booking->date_from);
        if($booking->delete()){
            return response()->json([
                'msg' => 'Deleted Activity',
                'icon'  => 'success'
            ], 200);
        }
    }
    
    public function booking(array $data, Activity $activity)
    {
        $existingIds = []; // Array to track processed IDs

        $data = array_filter($data, function ($item) {
            // Retain only objects without an 'id' key
            return !isset($item->id);
        });
        
        $data = array_map(function ($item) use ($activity) {
            // If product_id is empty, set free_type to name
            if (empty($item->product_id)) {
                $item->free_type = $item->name;
            } else {
                $item->free_type = null;
            }
        
            unset($item->name); // Remove the 'name' key if it exists
        
            // Handle potential null values and ensure activity_id is set
            $item->product_id = $item->product_id ?? null;
            $item->price = $item->price;
            $item->qty = $item->qty;
            $item->activity_id = $activity->id;
        
            return (array) $item; // Convert the item to an array
        }, $data);
        
        $data;
        
        DB::transaction(function () use ($data) {
            Booking::insert($data); // Bulk insert
        });
    }

}

