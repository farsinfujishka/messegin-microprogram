<?php

namespace App\Jobs;

use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendWhatsAppEinvoiceSend implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(public mixed $key, public mixed $data, public ?string $link = null)
    {
        $this->key = $key;
        $this->data = $data;
        $this->link = $link;
    }

    public function handle(): void
    {
        $token = $this->key['WHATSAPP_TOKEN'];
        $phoneNumberId = $this->key['WHATSAPP_PHONE_NUMBER_ID'];
        $version = config('services.whatsapp.version');
        $url = "https://graph.facebook.com/{$version}/{$phoneNumberId}/messages";

        $phone = $this->formatPhoneNumber($this->data['phone']);

        $response = Http::withToken($token)
            ->timeout(30)
            ->post($url, [
                'messaging_product' => 'whatsapp',
                'to' => $phone,
                'type' => 'template',
                'template' => [
                    'name' => 'e_invoicing',
                    'language' => ['code' => 'en'],
                    'components' => [
                        [
                            'type' => 'body',
                            'parameters' => [
                                ['type' => 'text', 'text' => $this->data['customer_name'] ?? 'Customer'],
                                ['type' => 'text', 'text' => $this->data['invoice_no'] ?? 'INV-0001'],
                                ['type' => 'text', 'text' => $this->data['amount'] ?? '100.00'],
                                ['type' => 'text', 'text' => $this->link ?? 'https://example.com/invoice/INV-0001'],
                                ['type' => 'text', 'text' => $this->data['company_name'] ?? 'Company'],
                            ],
                        ],
                    ],
                ],
            ]);

        if ($response->failed()) {
            throw new Exception('WhatsApp API failed: ' . $response->body());
        }
    }


    private function formatPhoneNumber(string $phone): string
    {
        $cleaned = preg_replace('/\D/', '', $phone);
        $defaultCode = (string) config('services.whatsapp.default_country_code', '91');
        if (!str_starts_with($cleaned, $defaultCode)) {
            $cleaned = $defaultCode . ltrim($cleaned, '0');
        }
        return $cleaned;
    }


    public function failed(Throwable $exception): void
    {
        Log::critical('WhatsApp welcome message permanently failed after retries', [
            'error' => $exception->getMessage(),
        ]);
    }
}
