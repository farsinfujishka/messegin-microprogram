<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait CheckPhoneInMetaList
{
    /**
     * Check if the phone number exists in the Meta contact list.
     */
    public function checkPhoneInMetaList(string $phone): bool
    {
        $accessToken   = config('services.whatsapp.access_token');
        $phoneNumberId = config('services.whatsapp.phone_number_id');

        $response = Http::withToken($accessToken)
            ->get("https://graph.facebook.com/v19.0/{$phoneNumberId}/contacts", [
                'phone' => $phone,
            ]);

            dd($response->body());

        if ($response->successful()) {
            $contacts = $response->json('data', []);
            return collect($contacts)->contains('phone_number', $phone);
        }

        Log::warning("Meta contact list check failed for phone: {$phone}", [
            'response' => $response->body(),
        ]);

        return false; // Treat as not in list on failure
    }

    /**
     * Add the phone number to the Meta contact list.
     */
    public function addPhoneToMetaList(string $phone, array $data): void
    {
        $accessToken   = config('services.whatsapp.access_token');
        $phoneNumberId = config('services.whatsapp.phone_number_id');

        $response = Http::withToken($accessToken)
            ->post("https://graph.facebook.com/v19.0/{$phoneNumberId}/contacts", [
                'phone_number' => $phone,
                'name'         => $data['customer_name'] ?? '',
            ]);

        if (!$response->successful()) {
            Log::error("Failed to add phone to Meta list: {$phone}", [
                'response' => $response->body(),
            ]);

            throw new \Exception("Could not add phone {$phone} to Meta contact list.");
        }

        Log::info("Phone {$phone} added to Meta contact list successfully.");
    }
}