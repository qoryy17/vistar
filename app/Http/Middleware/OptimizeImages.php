<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Spatie\ImageOptimizer\OptimizerChain;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class OptimizeImages
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $optimizerChain = app(OptimizerChain::class);

        collect($request->allFiles())
            ->flatten()
            ->filter(function (UploadedFile $file) {
                if (app()->environment('testing')) {
                    return true;
                }

                return $file->isValid();
            })
            ->each(function (UploadedFile $file) use ($optimizerChain) {
                $optimizerChain
                    ->useLogger(Log::channel('image_optimizer'))
                    ->optimize($file->getPathname());
            });

        return $next($request);
    }
}
