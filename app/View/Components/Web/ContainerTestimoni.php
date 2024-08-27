<?php

namespace App\View\Components\web;

use Closure;
use Illuminate\View\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;

class ContainerTestimoni extends Component
{
    public $testimoni;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->testimoni = DB::table('testimoni')->select(
            'testimoni.*',
            'customer.nama_lengkap',
            'customer.pendidikan',
            'customer.jurusan',
            'customer.foto'
        )->leftJoin('customer', 'testimoni.customer_id', '=', 'customer.id')
            ->where('publish', 'Y')->orderBy('updated_at', 'desc')->limit(10);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components..web.container-testimoni');
    }
}
