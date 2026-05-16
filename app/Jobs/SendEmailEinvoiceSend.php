<?php

namespace App\Jobs;

use App\Mail\CustomerEinvoiceSending;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendEmailEinvoiceSend implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(public mixed $data)
    {
        $this->data = $data;
    }

    public function handle(): void
    {
        $email = $this->data['email'];

        $payload = [
            'customer_name'           => $this->data['customer_name'],
            'invoice_no'             => $this->data['invoice_no'],
            'url_link'              => $this->data['url_link'],
            'company_name'          => $this->data['company_name'],
        ];

        $sent = Mail::to($email)->send(new CustomerEinvoiceSending($payload));

        if ($sent === null) {
            throw new Exception('Email sending failed: Mail::to()->send() returned null.');
        }
    }

    public function failed(Throwable $exception): void
    {
        Log::critical('Email welcome message permanently failed after retries', [
            'error' => $exception->getMessage(),
        ]);
    }
}