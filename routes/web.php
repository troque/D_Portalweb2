<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\NotificationsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('dashboard',  [
    DashboardController::class, 'show',
])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('dashboard/getInformacion',  [
    DashboardController::class, 'getInformacion',
])->middleware(['auth', 'verified'])->name('dashboard/getInformacion');

Route::get('notifications/getInformacion',  [
    NotificationsController::class, 'getInformacion',
])->middleware(['auth', 'verified'])->name('notifications/getInformacion');

require __DIR__ . '/auth.php';