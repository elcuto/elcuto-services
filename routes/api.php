<?php

use App\Http\Controllers\AT\OptinController;
use App\Http\Controllers\AT\MONotificationsController;
use App\Http\Controllers\Vodafone\DCBController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('at')->group(function () {
    Route::post('/notification/user-optin/{partnerRole}', [OptinController::class, 'customerOptin'])->name('user-optin');
    Route::post('/notification/user-optout/{partnerRole}', [OptinController::class, 'customerOptout'])->name('user-optout');
    Route::post('/notification/user-renewed/{partnerRole}', [OptinController::class, 'customerOptin'])->name('renew');
    Route::post('/notification/mo/{partnerRole}', [MONotificationsController::class, 'moReceivedSMS']);
});

Route::prefix('vf')->group(function () {
    Route::any('/notification/user-account/CustomerAccount', [DCBController::class, 'CreateCustomerAccount']);
    Route::any('/notification/user-account/DeleteCustomerAccount', [DCBController::class, 'DeleteCustomerAccount']);
});
