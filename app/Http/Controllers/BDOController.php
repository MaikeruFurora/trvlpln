<?php

namespace App\Http\Controllers;

use App\Models\ActivityList;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BDOController extends Controller
{
    
    public function beatplanWeeklyPrintout()
    {
        // Get the current week range (Monday to Saturday)
        $monday   = Carbon::now()->startOfWeek()->format('Y-m-d');
        $saturday = Carbon::now()->endOfWeek()->format('Y-m-d');
    
        // Fetch the current authenticated user
        $user = User::find(auth()->user()->id);
    
        // Get all activities for the current user within the Monday to Saturday date range
        $activities = $user->activities()
                           ->whereBetween('date_from', [$monday, $saturday])
                           ->with('activity_list:id,name,color,icon')
                           ->get()
                           ->groupBy(function ($activity) {
                               // Group activities by day of the week (Monday, Tuesday, etc.)
                               return Carbon::parse($activity->date_from)->format('l');
                           });
    
        // Pass data to the view
        return view('calendar.print.weekly-beatplan-printout', compact('activities', 'user', 'monday', 'saturday'));
    }
    


    public function weeklyPrintout(){
        // week range
        $monday       = Carbon::now()->startOfWeek()->format('Y-m-d'); 
        $saturday     = Carbon::now()->endOfWeek()->format('Y-m-d');
        $user     = User::find(auth()->user()->id);
        $activities = $user->activities()
                    ->whereBetween('date_from', [$monday, $saturday])
                    ->with('activity_list:id,name,color,icon','bookings:id,activity_id,product_id,qty,price,free_type','bookings.product:id,name')
                    ->get()
                    ->groupBy(function ($activity) {
                        return Carbon::parse($activity->date_from)->format('l');
                    });
        return view('calendar.print.weekly-printout',compact('activities','user','monday','saturday'));
    }

    public function todayPrintout(){
        $user = User::find(auth()->user()->id);
        $activities = $user->activities()
                    ->whereDate('date_from', Carbon::today())
                    ->with('activity_list:id,name,color,icon', 'bookings:id,activity_id,product_id,qty,price,free_type', 'bookings.product:id,name')
                    ->get();

        $categories = ActivityList::selectRaw('name, count(*) as count')
            ->join('activities', 'activity_lists.id', '=', 'activities.activity_list_id')
            ->where('activities.user_id', $user->id)
            ->whereDate('activities.date_from', Carbon::today())
            ->groupBy('activity_lists.name')
            ->get();
        
        
        return view('calendar.print.daily-printout', compact('activities', 'user','categories'));
    }
}
