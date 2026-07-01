<?php

use App\Http\Controllers\Admin\Auth\AuthController;
use App\Http\Controllers\Admin\Queue\QueueMonitorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('login', [AuthController::class, 'index'])->name('index');
Route::post('login', [AuthController::class, 'login'])->name('login');

Route::group(['middleware' => ['auth']], function () {
    Route::prefix('queue')->group(function () {
        Route::get('/', [QueueMonitorController::class, 'index'])->name('queue.index');
        Route::get('stats', [QueueMonitorController::class, 'stats']);
        Route::get('queues', [QueueMonitorController::class, 'queues']);
        Route::get('pending', [QueueMonitorController::class, 'pending']);
        Route::get('failed', [QueueMonitorController::class, 'failed']);
        Route::get('throughput', [QueueMonitorController::class, 'throughput']);
        Route::post('failed/{uuid}/retry', [QueueMonitorController::class, 'retry']);
        Route::delete('failed/{uuid}', [QueueMonitorController::class, 'forget']);
        Route::delete('failed', [QueueMonitorController::class, 'forgetAll']);
    });

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});
