<?php

use App\Livewire\CalculadoraSolar;
use Illuminate\Support\Facades\Route;

Route::view('/', 'pitch')->name('pitch');
Route::get('/demo', CalculadoraSolar::class)->name('demo');
