<?php

namespace App\View\Components\Web;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class HeaderAuth extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $title,
    ) {
        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components..web.header-auth');
    }
}
