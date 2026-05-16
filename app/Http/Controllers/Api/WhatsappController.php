<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendWhatsAppEinvoiceSend;
use App\Traits\CheckPhoneInMetaList;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;

class WhatsappController extends Controller
{
    public function einvoice(Request $request)
    {
        // $request->validate([
        //     'customer_name' => 'required|string|max:200',
        //     'phone'         => 'required|string|max:20',
        //     'invoice_no'    => 'required|string|max:20',
        //     'url_link'      => 'required|url',  
        //     'company_name'  => 'required|string|max:255',
        // ]);

        $data = $request->all();

        try {
            SendWhatsAppEinvoiceSend::dispatch($data)->onQueue('notifications');

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