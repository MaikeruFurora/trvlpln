<?php

namespace App\Exports\Report;

use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class WeeklyReport extends DefaultValueBinder implements ShouldAutoSize, FromView, WithTitle, WithCustomValueBinder
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {

        $monday       = Carbon::now()->startOfWeek()->format('Y-m-d'); 
        $saturday     = Carbon::now()->endOfWeek()->format('Y-m-d');
        $user         = User::find(auth()->user()->id);
        $activities   = $user->activities()
                        ->whereBetween('date_from', [$monday, $saturday])
                        ->with('activity_list:id,name,color,icon','bookings:id,activity_id,product_id,qty,price,free_type','bookings.product:id,name')
                        ->get()
                        ->groupBy(function ($activity) {
                            return Carbon::parse($activity->date_from)->format('l');
                        }); 
       return view('calendar.excel.weeklyReport',[

           'user'        => $user,

           'monday'      => $monday,
           
           'saturday'    => $saturday,

           'activities'  => $activities,

       ]);
    }

    public function title(): string
    {
        return floor(microtime(true) * 1000);
    }
}
