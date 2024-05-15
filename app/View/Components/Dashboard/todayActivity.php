<?php

namespace App\View\Components\Dashboard;

use Illuminate\View\Component;

class todayActivity extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

     public $dataTable;

    public function __construct($dataTable)
    {
       $this->dataTable = $dataTable;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.dashboard.today-activity');
    }
}
