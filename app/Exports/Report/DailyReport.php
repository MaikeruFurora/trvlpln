<?php

namespace App\Exports\Report;

use App\Models\ActivityList;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class DailyReport extends DefaultValueBinder implements ShouldAutoSize, FromView, WithTitle, WithCustomValueBinder
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {

        $user = User::find(auth()->user()->id);
        $activities = $user
                    ->activities()
                    ->whereDate('date_from', Carbon::today())
                    ->with('activity_list:id,name,color,icon', 'bookings:id,activity_id,product_id,qty,price,free_type', 'bookings.product:id,name')
                    ->get();
                

        $categories = ActivityList::selectRaw('name, count(*) as count')
            ->join('activities', 'activity_lists.id', '=', 'activities.activity_list_id')
            ->where('activities.user_id', $user->id)
            ->whereDate('activities.date_from', Carbon::today())
            ->groupBy('activity_lists.name')
            ->get();

       return view('calendar.excel.dailyReport',[

           'user'        => $user,

           'date'        => Carbon::now(),

           'activities'  => $activities,

           'categories'  => $categories, 

       ]);
    }

    public function title(): string
    {
        return floor(microtime(true) * 1000);
    }
}
