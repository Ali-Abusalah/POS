<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerApiController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\InvoiceApiController;
use App\Http\Controllers\Api\ItemApiController;
use App\Http\Controllers\POSApiController;
use App\Http\Controllers\Api\ReportApiController;
use App\Http\Controllers\Api\SettingApiController;
use App\Http\Controllers\Api\QuoteApiController;
use App\Http\Controllers\Api\SubscriptionApiController;
use App\Http\Controllers\Api\CreditNoteApiController;
use Illuminate\Support\Facades\Route;

// Auth
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout']);
Route::get('/auth/me', [AuthController::class, 'me']);

// POS
Route::get('/pos/products', [POSApiController::class, 'products']);
Route::get('/settings/public', [SettingApiController::class, 'publicSettings']);

Route::middleware('auth:web')->group(function () {
    // POS
    Route::post('/pos/invoice', [POSApiController::class, 'createInvoice']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Invoices
    Route::get('/invoices', [InvoiceApiController::class, 'index']);
    Route::get('/invoices/{invoice}', [InvoiceApiController::class, 'show']);
    Route::post('/invoices/{invoice}/void', [InvoiceApiController::class, 'void']);
    Route::post('/invoices/{invoice}/refund', [InvoiceApiController::class, 'refund']);

    // Items
    Route::get('/items', [ItemApiController::class, 'index']);
    Route::post('/items', [ItemApiController::class, 'store']);
    Route::post('/items/{item}', [ItemApiController::class, 'update']);
    Route::delete('/items/{item}', [ItemApiController::class, 'destroy']);

    // Customers
    Route::get('/customers', [CustomerApiController::class, 'index']);
    Route::post('/customers', [CustomerApiController::class, 'store']);
    Route::post('/customers/{customer}', [CustomerApiController::class, 'update']);
    Route::delete('/customers/{customer}', [CustomerApiController::class, 'destroy']);

    // Reports
    Route::get('/reports/sales', [ReportApiController::class, 'sales']);

    // Quotes
    Route::get('/quotes', [QuoteApiController::class, 'index']);
    Route::get('/quotes/{quote}', [QuoteApiController::class, 'show']);
    Route::post('/quotes', [QuoteApiController::class, 'store']);
    Route::post('/quotes/{quote}', [QuoteApiController::class, 'update']);
    Route::delete('/quotes/{quote}', [QuoteApiController::class, 'destroy']);
    Route::post('/quotes/{quote}/convert', [QuoteApiController::class, 'convertToInvoice']);

    // Subscriptions
    Route::get('/subscriptions', [SubscriptionApiController::class, 'index']);
    Route::get('/subscriptions/{subscription}', [SubscriptionApiController::class, 'show']);
    Route::post('/subscriptions', [SubscriptionApiController::class, 'store']);
    Route::post('/subscriptions/{subscription}', [SubscriptionApiController::class, 'update']);
    Route::delete('/subscriptions/{subscription}', [SubscriptionApiController::class, 'destroy']);
    Route::post('/subscriptions/{subscription}/pause', [SubscriptionApiController::class, 'pause']);
    Route::post('/subscriptions/{subscription}/resume', [SubscriptionApiController::class, 'resume']);
    Route::post('/subscriptions/{subscription}/cancel', [SubscriptionApiController::class, 'cancel']);

    // Credit Notes
    Route::get('/credit-notes', [CreditNoteApiController::class, 'index']);
    Route::get('/credit-notes/{creditNote}', [CreditNoteApiController::class, 'show']);
    Route::post('/credit-notes', [CreditNoteApiController::class, 'store']);
    Route::post('/credit-notes/{creditNote}', [CreditNoteApiController::class, 'update']);
    Route::delete('/credit-notes/{creditNote}', [CreditNoteApiController::class, 'destroy']);
    Route::post('/credit-notes/{creditNote}/apply', [CreditNoteApiController::class, 'apply']);

    // Settings
    Route::get('/settings', [SettingApiController::class, 'index']);
    Route::put('/settings', [SettingApiController::class, 'update']);
});
