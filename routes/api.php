<?php

use App\Http\Controllers\Api\EmailController;
use App\Http\Controllers\Api\WhatsappController;
use Illuminate\Support\Facades\Route;

Route::post('/whatsapp/einvoice', [WhatsappController::class, 'einvoice'])->name('api.whatsapp.einvoice');
Route::post('/email/einvoice', [EmailController::class, 'einvoice'])->name('api.email.einvoice');