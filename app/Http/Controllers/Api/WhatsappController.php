<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendWhatsAppEinvoiceSend;
use App\Rules\WhatsAppNumber;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class WhatsappController extends Controller
{
    public function einvoice(Request $request)
    {
        // dd('WhatsApp e-invoice endpoint hit', $request->all());
        $validator = Validator::make($request->all(), [
            'key.WHATSAPP_TOKEN'               => 'required|string',
            'key.WHATSAPP_PHONE_NUMBER_ID'     => 'required|string',
            'key.WHATSAPP_BUSINESS_ACCOUNT_ID' => 'required|string',
            'link'                             => 'required|url',
            'data.customer_name'               => 'required|string|max:200',
            'data.phone'                       => ['required', 'string', 'max:20', new WhatsAppNumber($request->key)],
            'data.invoice_no'                  => 'required|string|max:20',
            'data.company_name'                => 'required|string|max:255',
            'data.amount'                      => 'required|numeric',
        ],[
            'key.WHATSAPP_TOKEN.required'               => 'WhatsApp token is required.',
            'key.WHATSAPP_TOKEN.string'                 => 'WhatsApp token must be a string.',
            'key.WHATSAPP_PHONE_NUMBER_ID.required'     => 'WhatsApp phone number ID is required.',
            'key.WHATSAPP_PHONE_NUMBER_ID.string'       => 'WhatsApp phone number ID must be a string.',
            'key.WHATSAPP_BUSINESS_ACCOUNT_ID.required' => 'WhatsApp business account ID is required.',
            'key.WHATSAPP_BUSINESS_ACCOUNT_ID.string'   => 'WhatsApp business account ID must be a string.',
            'link.required'                             => 'Link is required.',
            'link.url'                                  => 'Link must be a valid URL.',
            'data.customer_name.required'               => 'Customer name is required.',
            'data.customer_name.string'                 => 'Customer name must be a string.',
            'data.customer_name.max'                    => 'Customer name must be at most 200 characters.',
            'data.phone.required'                       => 'Phone number is required.',
            'data.phone.string'                         => 'Phone number must be a string.',
            'data.phone.max'                            => 'Phone number must be at most 20 characters.',
            'data.phone.WhatsAppNumber'                 => 'This number not available in whatsapp.',
            'data.invoice_no.required'                  => 'Invoice number is required.',
            'data.invoice_no.string'                    => 'Invoice number must be a string.',
            'data.invoice_no.max'                       => 'Invoice number must be at most 20 characters.',
            'data.company_name.required'                => 'Company name is required.',
            'data.company_name.string'                  => 'Company name must be a string.',
            'data.company_name.max'                     => 'Company name must be at most 255 characters.',
            'data.amount.required'                      => 'Amount is required.',
            'data.amount.numeric'                       => 'Amount must be a numeric value.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $key = $request->key;
        $data = $request->data;
        $link = $request->link;

        try {
            SendWhatsAppEinvoiceSend::dispatch($key, $data, $link)->onQueue('notifications');

            return response()->json([
                'success' => true,
                'message' => 'WhatsApp e-invoice queued successfully.',
            ], 200);
        } catch (Exception $e) {
            Log::error('WhatsApp e-invoice dispatch failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to queue WhatsApp e-invoice.',
            ], 500);
        }
    }
}
