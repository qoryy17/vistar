<?php

namespace App\View\Components\Web;

use App\Helpers\BerandaUI;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ContainerCounterPerserta extends Component
{
    public $web;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->web = BerandaUI::web();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View | Closure | string
    {
        return view('components.web.container-counter-perserta');
    }
}
