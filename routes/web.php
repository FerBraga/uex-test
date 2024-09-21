<?php

use App\Http\Controllers\Contact\ContactController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/home', function () {
    return view('home');
})->middleware(['auth', 'verified'])->name('home');



Route::middleware('auth', 'verified')->group(function () {
    Route::get('/home', [ContactController::class, 'list'])->name('home');
    Route::post('/contact/store',[ContactController::class, 'store'])->name('contact.store');
    Route::put('/contact/edit',[ContactController::class, 'edit'])->name('contacts.edit');
    Route::delete('/contact/delete',[ContactController::class, 'destroy'])->name('contacts.destroy');
    Route::get('/get-address', [ContactController::class, 'getAddress'])->name('get.address');
    Route::get('/get-cep', [ContactController::class, 'getCep'])->name('get.cep');
    Route::get('/get-map', [ContactController::class, 'getMap'])->name('get.map');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
