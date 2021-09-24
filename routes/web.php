<?php

use App\Http\Livewire\Backend\UserDetails;
use App\Http\Livewire\Backend\Bets;
use App\Http\Livewire\Backend\Login;
use App\Http\Livewire\Backend\Users;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Backend\Dashboard;
use App\Http\Livewire\Backend\BetHistory;
use App\Http\Livewire\Backend\Categories;
use App\Http\Livewire\Backend\ReportUsers;

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

Route::get('/', Login::class)->name('login');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/users', Users::class)->name('users');
    Route::get('/categories', Categories::class)->name('categories');
    Route::get('/bets', Bets::class)->name('bets');
    Route::get('/history', BetHistory::class)->name('bets.history');
    Route::get('/report/users', ReportUsers::class)->name('report.users');
    Route::get('/user-details/{user_id}', UserDetails::class)->name('user-details');
    Route::get('/logout', function () {
        auth()->logout();
        return redirect('/');
    })->name('logout');
});
Route::get('/tester', [App\Http\Controllers\TestController::class, 'index'])->name('tester');
