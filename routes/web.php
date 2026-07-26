<?php

use App\Livewire\Dashboard\Index;
use App\Livewire\Contracts\Index as ContractIndex;
use App\Livewire\Contracts\Show as ContractShow;
use App\Livewire\Contracts\Create as ContractCreate;
use App\Livewire\Contracts\Edit as ContractEdit;

use App\Livewire\ContractAddendums\Edit as ContractAddendumEdit;
use App\Livewire\ContractAddendums\Create as ContractAddendumCreate;

use Illuminate\Support\Facades\Route;

Route::get('/', Index::class)->name('dashboard');

Route::get('/contracts', ContractIndex::class)->name('contracts.index');
Route::get('/contracts/{contract}', ContractShow::class)->name('contracts.show');
Route::get('/contracts/create', ContractCreate::class)->name('contracts.create');
Route::get('/contracts/{contract}/edit', ContractEdit::class)->name('contracts.edit');

Route::get('/contracts/{contract}/addendums/create', ContractAddendumCreate::class)->name('contract-addendums.create');
Route::get('/contract-addendums/{contractAddendum}/edit', ContractAddendumEdit::class)->name('contract-addendums.edit');