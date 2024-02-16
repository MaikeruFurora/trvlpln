<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ActivityForm extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $lists;

    public function __construct($lists)
    {
        $this->lists=$lists;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.activity-form');
    }
}
