<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SimpleController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PemainController;
use App\Http\Controllers\Auth\LoginRegisterController;
use App\Http\Controllers\CheckAge;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\SendEmailController;



// Default route
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// About page
Route::get('/about', function () {
    return view('about', [
        "name" => "jay",
        "email" => "jay@gmail.com"
    ]);
});

// Post index
Route::get('/post', [PostController::class, 'index']);

// Simple resource controller
Route::resource('simple', SimpleController::class);

// Rute untuk pemain
Route::get('/pemain', [PemainController::class, 'index'])->name('pemain.index');

// Rute untuk LoginRegister
Route::controller(LoginRegisterController::class)->group(function () {
    Route::get('/register', 'register')->name('register');
    Route::post('/store', 'store')->name('store');
    Route::get('/login', 'login')->name('login');
    Route::post('/authenticate', 'authenticate')->name('authenticate');
    Route::post('/logout', 'logout')->name('logout');
    Route::get('/dashboard', 'dashboard')->name('dashboard'); //tugas pertemuan 9
});

// Rute untuk Buku dan Dashboard
Route::middleware(['auth'])->group(function() {
    // Rute untuk Buku
    Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
    Route::get('/buku/create', [BukuController::class, 'create'])->name('buku.create');
    Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');
    Route::get('/buku/{id}/edit', [BukuController::class, 'edit'])->name('buku.edit');
    Route::put('/buku/{id}', [BukuController::class, 'update'])->name('buku.update');
    Route::delete('/buku/{id}', [BukuController::class, 'destroy'])->name('buku.destroy');

    // Rute untuk Dashboard
    Route::get('/dashboard', [BukuController::class, 'dashboard'])->name('dashboard'); // Ganti BukuController dengan controller yang sesuai jika perlu
});

//pertemuan 9
Route::get('restricted', function() {
    return redirect(route('dashboard'))->with('success', 'Anda berusia lebih dari 18 tahun!');
})->middleware('checkage');

//pertemuan 11
Route::resource('gallery', GalleryController::class);

//pertemuan 12
Route::get('/send-mail', [SendEmailController::class, 'index'])->name('kirim-email');
Route::post('/post-email', [SendEmailController::class, 'store'])->name('post-email');

// routes/api.php

Route::get('/galleries', [GalleryController::class, 'index']);
