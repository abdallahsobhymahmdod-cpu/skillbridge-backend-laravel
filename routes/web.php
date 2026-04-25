<?php

use App\Http\Controllers\BookingController;
use App\Http\Middleware\IsAbdallahUser;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/abd', function () {
    $students = ['Abdallah', 'Sobhy', 'Ali', 'Omar'];

    $grad = 70;
    $x = 'Sobhy';
    $y = 'My Name Is Abdallah Sobhy I Have 19 Years Old';

    return view('abdallah')
        ->with('Last_name', $x)
        ->with('details', $y)
        ->with('grads', $grad)
        ->with('student', $students);
});

Route::get('/mybookings/{name}', BookingController::class . '@mybookings')
    ->middleware(IsAbdallahUser::class);

Route::get('/sayhello/{name}', BookingController::class . '@sayhello')
    ->middleware(IsAbdallahUser::class);

Route::get('/login', function () {
    return response()->json([
        'status' => false,
        'message' => 'Unauthenticated.',
    ], 401);
})->name('login');