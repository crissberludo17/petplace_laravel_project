<?php

use Illuminate\Support\Facades\Route;

// ── HOME ──────────────────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
});

// ── CHOOSE ACCOUNT ────────────────────────────────────────
Route::get('/choose', function () {
    return view('choose');
})->name('choose');

// ── VET AUTH ──────────────────────────────────────────────
Route::get('/login/vet', function () {
    return view('auth.login-vet');
})->name('login.vet');

Route::get('/register/vet', function () {
    return view('auth.register-vet');
})->name('register.vet');

// ── OWNER AUTH ────────────────────────────────────────────
Route::get('/login/owner', function () {
    return view('auth.login-owner');
})->name('login.owner');

Route::get('/register/owner', function () {
    return view('auth.register-owner');
})->name('register.owner');

// ── DASHBOARDS (protected) ────────────────────────────────
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile routes (required by Breeze navigation)
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

    // Vet dashboard
    Route::get('/vet/dashboard', function () {
        if (auth()->user()->role !== 'vet') {
            return redirect()->route('owner.dashboard');
        }
        return view('vet.dashboard');
    })->name('vet.dashboard');

    // Owner dashboard
    Route::get('/owner/dashboard', function () {
        if (auth()->user()->role !== 'owner') {
            return redirect()->route('vet.dashboard');
        }
        return view('owner.dashboard');
    })->name('owner.dashboard');


    // Vet dashboard
    Route::get('/vet/dashboard', function () {
        if (auth()->user()->role !== 'vet') {
            return redirect()->route('owner.dashboard');
        }
        return view('vet.dashboard');
    })->name('vet.dashboard');

    // Owner dashboard
    Route::get('/owner/dashboard', function () {
        if (auth()->user()->role !== 'owner') {
            return redirect()->route('vet.dashboard');
        }
        return view('owner.dashboard');
    })->name('owner.dashboard');

});

// ── BREEZE AUTH ROUTES ────────────────────────────────────
require __DIR__.'/auth.php';