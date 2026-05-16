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

    public function __construct(public mixed $data)
    {
        $this->data = $data;
    }

    public function handle(): void
    {
        $token = config('services.whatsapp.token');
        $phoneNumberId = config('services.whatsapp.phone_number_id');
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
                    'name' => 'customer_welcome_onboard',
                    'language' => ['code' => 'en'],
                    'components' => [
                        [
                            'type' => 'body',
                            'parameters' => [
                                ['type' => 'text', 'text' => $this->data['customer_name']],
                                ['type' => 'text', 'text' => $this->data['customer_code'] ?? 'N/A'],
                                ['type' => 'text', 'text' => Carbon::parse($this->data['subscription_start_date'])->format('d M Y')],
                                ['type' => 'text', 'text' => Carbon::parse($this->data['subscription_end_date'])->format('d M Y')],
                                ['type' => 'text', 'text' => (string) $this->data['user_limit']],
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
        $defaultCode = config('services.whatsapp.default_country_code', '91');
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
