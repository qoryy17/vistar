<?php

namespace App\View\Components\Web;

use App\Models\Testimoni;
use Cache;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ContainerTestimoni extends Component
{
    public $testimoni;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $testimoni = Cache::remember('testimoni_main_web', 7 * 24 * 60 * 60, function () {
            return Testimoni::where('testimoni.publish', 'Y')
                ->select(
                    'testimoni.id',
                    'testimoni.testimoni',
                    'testimoni.rating',
                    'testimoni.created_at',

                    'produk_tryout.id as product_id',
                    'produk_tryout.nama_tryout as product_name',
                    'produk_tryout.thumbnail as product_thumbnail',

                    'customer.nama_lengkap as user_name',
                    'customer.pendidikan as user_pendidikan',
                    'customer.jurusan as user_jurusan',
                    'customer.foto as user_photo'
                )
                ->leftJoin('customer', 'testimoni.customer_id', '=', 'customer.id')
                ->leftJoin('produk_tryout', 'testimoni.produk_tryout_id', '=', 'produk_tryout.id')
                ->orderBy('testimoni.updated_at', 'desc')
                ->limit(10)
                ->get();
        });

        $this->testimoni = $testimoni;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View | Closure | string
    {
        return view('components.web.container-testimoni');
    }
}
