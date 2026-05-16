<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerEinvoiceSending extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public array $data) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Your E-Invoice #{$this->data['invoice_no']} from {$this->data['company_name']} is Ready",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'Emails.e-invoice-sending',
            with: $this->data,
        );
    }
}
