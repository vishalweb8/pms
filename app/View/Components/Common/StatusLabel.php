<?php

namespace App\View\Components\Common;

use Illuminate\View\Component;

class StatusLabel extends Component
{
    public $class;
    public $title;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($class, $title)
    {
        $this->class = $class;
        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.common.status-label');
    }
}
