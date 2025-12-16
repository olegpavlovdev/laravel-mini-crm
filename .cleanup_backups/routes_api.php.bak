<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TicketController;

Route::post('/tickets', [TicketController::class, 'store'])->middleware('check.ticket.rate');
Route::get('/tickets/statistics', [TicketController::class, 'statistics']);
