<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailEinvoiceSend;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class EmailController extends Controller
{
    public function einvoice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:200',
            'email'         => 'required|string|email|max:255',
            'invoice_no'    => 'required|string|max:20',
            'url_link'      => 'required|url',  
            'company_name'  => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $data = $request->all();

        try {
            SendEmailEinvoiceSend::dispatch($data)->onQueue('notifications');

            return response()->json([
                'success' => true,
                'message' => 'Email e-invoice queued successfully.',
            ], 200);

        } catch (Exception $e) {
            Log::error('Email e-invoice dispatch failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to queue Email e-invoice.',
            ], 500);
        }
    }
}
