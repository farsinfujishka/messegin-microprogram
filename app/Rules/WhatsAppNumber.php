<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppNumber implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $phone = $this->formatPhoneNumber($value);

        if (! $this->isRegisteredOnWhatsApp($phone)) {
            $fail('The :attribute is not a registered WhatsApp number.');
        }
    }

    private function isRegisteredOnWhatsApp(string $phone): bool
    {
        try {
            $token = config('services.whatsapp.token');
            $phoneNumberId = config('services.whatsapp.phone_number_id');
            $version = config('services.whatsapp.version');

            $response = Http::withToken($token)
                ->post("https://graph.facebook.com/{$version}/{$phoneNumberId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to'                => $phone,
                    'type'              => 'contacts',
                    'contacts'          => [
                        [
                            'name' => [
                                'formatted_name' => 'Validation Check',
                                'first_name'     => 'Validation',
                            ],
                            'phones' => [
                                [
                                    'phone' => $phone,
                                    'type'  => 'CELL',
                                ]
                            ],
                        ]
                    ],
                ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('WhatsApp number check failed: ' . $e->getMessage());
            return false;
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
}
