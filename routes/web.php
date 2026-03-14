<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Marketing\DashboardController as MarketingDashboard;
use App\Http\Controllers\KonsumenController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\TargetController;
use App\Http\Controllers\FollowUpController;
use App\Http\Controllers\UserController;
use App\Exports\KonsumenExport;
use Maatwebsite\Excel\Facades\Excel;

// ===============================
// HALAMAN LOGIN DEFAULT
// ===============================
Route::get('/', function () {
    return view('auth.login');
});

Route::get('/home', function () {
    return view('home');
})->name('home');

// ===============================
// AUTH ROUTES
// ===============================
Auth::routes([
    'register' => false,
    'reset' => false,
]);

// ===============================
// SEMUA ROUTE WAJIB LOGIN
// ===============================
Route::middleware(['auth'])->group(function () {

    // ===============================
    // REDIRECT BERDASARKAN ROLE
    // ===============================
    Route::get('/home', function () {
        $user = auth()->user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'marketing') {
            return redirect()->route('marketing.dashboard');
        }

        abort(403);
    })->name('home');

    // ===============================
    // DASHBOARD
    // ===============================
    Route::get('/admin/dashboard', [AdminDashboard::class, 'index'])
        ->middleware('role:admin')
        ->name('admin.dashboard');

    // Admin juga bisa akses dashboard marketing
    Route::get('/marketing/dashboard', [MarketingDashboard::class, 'index'])
        ->middleware('role:marketing,admin') // ✅ tambahkan admin
        ->name('marketing.dashboard');

    // ===============================
    // LIVE SEARCH
    // ===============================
    Route::get('/konsumen/live-search', [KonsumenController::class, 'liveSearch'])
        ->name('konsumen.liveSearch');

    Route::get('/followups/live-search', [FollowUpController::class, 'liveSearch'])
        ->name('followups.liveSearch');

    // ===============================
    // RESOURCE ROUTES
    // ===============================
    Route::resource('konsumen', KonsumenController::class)
        ->parameters(['konsumen' => 'konsumen'])
        ->except(['show']);

    Route::resource('followups', FollowUpController::class)
        ->parameters(['followups' => 'followup'])
        ->except(['show']);

    Route::put('/followups/{followup}', [FollowUpController::class, 'update'])
        ->name('followups.update');

    Route::resource('produk', ProdukController::class);
    Route::resource('targets', TargetController::class);
    Route::resource('transaksi', TransaksiController::class);

    // ===============================
    // USERS ROUTE HANYA UNTUK ADMIN
    // ===============================
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
    });

    // ===============================
    // EXPORT KONSUMEN
    // ===============================
    Route::get('/export-konsumen', function () {
        return Excel::download(new KonsumenExport, 'konsumen.xlsx');
    })->name('export.konsumen');

    // ===============================
    // AJAX / SPESIAL
    // ===============================
    Route::get('/marketing/followups-today', [MarketingDashboard::class, 'followupsToday'])
        ->name('marketing.followups.today');

    Route::delete('/followups/{followup}', [FollowUpController::class, 'destroy'])
        ->name('followups.destroy');

    // ===============================
    // ROLE REDIRECT OPSIONAL
    // ===============================
    Route::get('/redirect', function () {
        $user = auth()->user();

        if (!$user)
            return redirect('/login');

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'marketing') {
            return redirect()->route('marketing.dashboard');
        }

        abort(403);
    })->name('role.redirect');

    // ===============================
    // LOGOUT
    // ===============================
    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/');
    })->name('logout');

});

Route::post('/konsumen/import', [KonsumenController::class, 'import'])
    ->name('konsumen.import');

Route::get('/konsumen/export', [KonsumenController::class, 'export'])
    ->name('konsumen.export');
