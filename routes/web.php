<?php
use App\Http\Controllers\StockController;
use App\Http\Controllers\ProfileController;

// ✅ Redirecionamento para login — fora do middleware
Route::get('/', function () {
    return redirect()->route('login');
});

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/consultar', function () {
        return view('consultar');
    })->name('consultar');

    Route::get('/vender', function () {
        return view('vender');
    })->name('vender');

    Route::get('/dashboard', function () {
        return redirect('/consultar');
    })->name('dashboard');

    Route::post('/consultar', [StockController::class, 'consultar'])->name('consultar.acao');
    Route::get('/comprar', function () { return view('comprar'); })->name('comprar');
    Route::post('/comprar', [StockController::class, 'comprar'])->name('comprar.acao');
    Route::post('/vender', [StockController::class, 'vender'])->name('vender.acao');
    Route::get('/carteira', [StockController::class, 'carteira'])->name('carteira');
    Route::get('/historico', [StockController::class, 'historico'])->name('historico');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


