<?php

use App\Livewire\Auth\ChangePassword;
use App\Livewire\Auth\Login;
use App\Livewire\Dashboard\Index;
use App\Livewire\Contracts\Create as ContractCreate;
use App\Livewire\Contracts\Edit as ContractEdit;
use App\Livewire\Contracts\Index as ContractIndex;
use App\Livewire\Contracts\Show as ContractShow;

use App\Livewire\Counterparties\Index as CounterpartiesIndex;
use App\Livewire\Counterparties\Create as CounterpartiesCreate;
use App\Livewire\Counterparties\Show as CounterpartiesShow;
use App\Livewire\Counterparties\Edit as CounterpartiesEdit;

use App\Livewire\AdvertisingObjects\Index as AdvertisingObjectsIndex;
use App\Livewire\AdvertisingObjects\Create as AdvertisingObjectsCreate;
use App\Livewire\AdvertisingObjects\Show as AdvertisingObjectsShow;
use App\Livewire\AdvertisingObjects\Edit as AdvertisingObjectsEdit;

use App\Livewire\PhotoReports\Index as PhotoReportsIndex;
use App\Livewire\PhotoReports\Create as PhotoReportsCreate;
use App\Livewire\PhotoReports\Show as PhotoReportsShow;
use App\Livewire\PhotoReports\Edit as PhotoReportsEdit;

use App\Livewire\Map\Index as MapIndex;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
});

Route::middleware('auth')->group(function () {

    Route::get('/', Index::class)->name('dashboard');

    Route::get('/change-password', ChangePassword::class)->name('password.change');

    Route::get('/contracts', ContractIndex::class)->name('contracts.index');
    Route::get('/contracts/create', ContractCreate::class)->name('contracts.create');
    Route::get('/contracts/{contract}', ContractShow::class)->name('contracts.show');
    Route::get('/contracts/{contract}/edit', ContractEdit::class)->name('contracts.edit');

    Route::get('/counterparties', CounterpartiesIndex::class)->name('counterparties.index');
    Route::get('/counterparties/create', CounterpartiesCreate::class)->name('counterparties.create');
    Route::get('/counterparties/{counterparty}', CounterpartiesShow::class)->name('counterparties.show');
    Route::get('/counterparties/{counterparty}/edit', CounterpartiesEdit::class)->name('counterparties.edit');

    Route::get('/advertising-objects', AdvertisingObjectsIndex::class)->name('advertising-objects.index');
    Route::get('/advertising-objects/create', AdvertisingObjectsCreate::class)->name('advertising-objects.create');
    Route::get('/advertising-objects/{advertisingObject}', AdvertisingObjectsShow::class)->name('advertising-objects.show');
    Route::get('/advertising-objects/{advertisingObject}/edit', AdvertisingObjectsEdit::class)->name('advertising-objects.edit');

    Route::get('/photo-reports', PhotoReportsIndex::class)->name('photo-reports.index');
    Route::get('/photo-reports/create', PhotoReportsCreate::class)->name('photo-reports.create');
    Route::get('/photo-reports/{photoReport}', PhotoReportsShow::class)->name('photo-reports.show');
    Route::get('/photo-reports/{photoReport}/edit', PhotoReportsEdit::class)->name('photo-reports.edit');

    Route::get('/map', MapIndex::class)->name('map.index');

    Route::post('/logout', function () {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    })->name('logout');
});