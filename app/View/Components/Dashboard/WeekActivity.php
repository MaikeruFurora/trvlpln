<?php

namespace App\View\Components\Dashboard;

use Illuminate\View\Component;

class WeekActivity extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

     public $dataWeekTable;
    public function __construct($dataWeekTable)
    {
        $this->dataWeekTable = $dataWeekTable;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.dashboard.week-activity');
    }
}
