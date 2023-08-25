<?php

namespace App\View\Components\Common;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Heading extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $heading;
    public $btnText;
    public $btnUrl;

    public function __construct(string $heading='', string $btnText='', string $btnUrl='')
    {
        $this->heading = $heading;
        $this->btnText = $btnText;
        $this->btnUrl  = $btnUrl;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return Application|Factory|View
     */
    public function render()
    {
        return view('components.common.heading');
    }
}
