<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AccordionBukti extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $buktiShare,
        public string $buktiFollow,
    ) {
        $this->buktiShare = $buktiShare;
        $this->buktiFollow = $buktiFollow;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View | Closure | string
    {
        return view('components.accordion-bukti');
    }
}
