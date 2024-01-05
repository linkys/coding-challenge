<?php

use App\Http\Controllers\ConnectionController;
use App\Http\Controllers\RequestController;
use Illuminate\Support\Facades\Route;

Route::get('/suggestions', [ConnectionController::class, 'suggestions'])->name('suggestions');
Route::get('/connections', [ConnectionController::class, 'connections'])->name('connections');
Route::delete('/connections/{user}', [ConnectionController::class, 'removeConnection'])->name('connections.destroy');
Route::get('/connections/common/{user}', [ConnectionController::class, 'showCommonConnections'])->name('connections.common');

Route::prefix('/requests')->group(function () {
    Route::get('/sent', [RequestController::class, 'showSent'])->name('requests.sent');
    Route::get('/received', [RequestController::class, 'showReceived'])->name('requests.received');
    Route::post('/', [RequestController::class, 'store'])->name('requests.store');
    Route::delete('/{connectionRequest}', [RequestController::class, 'destroy'])->name('requests.destroy');
    Route::post('/{connectionRequest}/accept', [RequestController::class, 'accept'])->name('requests.accept');
});
