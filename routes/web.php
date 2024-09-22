<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Contact\ContactController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
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


    Route::get('/', function () {
        // Verifica se o usuário está autenticado
        if (Auth::check()) {
            // Se autenticado, redireciona para a home (ou onde você quiser)
            return redirect()->route('home');
        } else {
            // Se não estiver autenticado, redireciona para o signup
            return redirect()->route('register');
        }
    })->name('home');
    
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');


Route::middleware('auth', 'verified')->group(function () {
    Route::get('/home', [ContactController::class, 'list'])->name('home');
    Route::post('/contact',[ContactController::class, 'store'])->name('contact.store');
    Route::put('/contact/{id}',[ContactController::class, 'update'])->name('contact.update');
    Route::delete('/contact/{id}',[ContactController::class, 'destroy'])->name('contact.destroy');
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
