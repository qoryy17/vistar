<?php

namespace App\View\Components\web;

use App\Helpers\BerandaUI;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ContainerCobaGratis extends Component
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
    public function render(): View|Closure|string
    {
        return view('components..web.container-coba-gratis');
    }
}
