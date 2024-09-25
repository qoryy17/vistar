<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Accordion extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $jawabanA,
        public string $jawabanB,
        public string $jawabanC,
        public string $jawabanD,
        public string $jawabanE,
        public string $berbobot,
        public string $kunciJawaban,
        public string $reviewPembahasan,
    ) {
        $this->jawabanA = $jawabanA;
        $this->jawabanB = $jawabanB;
        $this->jawabanC = $jawabanC;
        $this->jawabanD = $jawabanD;
        $this->jawabanE = $jawabanE;
        $this->berbobot = $berbobot;
        $this->kunciJawaban = $kunciJawaban;
        $this->reviewPembahasan = $reviewPembahasan;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View | Closure | string
    {
        return view('components.accordion');
    }
}
