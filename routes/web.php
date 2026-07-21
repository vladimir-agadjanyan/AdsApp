<?php

use App\Livewire\Dashboard\Index;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', Index::class)->name('dashboard');
