<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Mail;
use App\Mail\EmailAcceptTryoutGratis;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendAcceptTryoutGratisJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 10;
    protected $email;

    /**
     * Create a new job instance.
     */
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Kirim email pengajuan diterima
        Mail::to($this->email)->send(new EmailAcceptTryoutGratis());
    }
}
