<?php

use App\Http\Controllers\CupCakeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OtherCupcakeController;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureUserHasRole;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('test', function () {
    $user = User::with('orders.cupcakes')->get();

    return $user;
    //return UserResource::collection(User::all());
});

//Routes pour cupcakes
Route::get('/cupCakes', [OtherCupcakeController::class, 'index'])->middleware('auth');
Route::get('/cupCake/{cupCake}', [OtherCupcakeController::class, 'show'])->middleware('auth');
Route::post('/cupCake', [OtherCupcakeController::class, 'store'])->middleware('auth', 'role');
Route::put('/cupCake/{cupCake}', [OtherCupcakeController::class, 'update'])->middleware('auth', 'role');
Route::delete('/cupCake/{cupCake}', [OtherCupcakeController::class, 'destroy'])->middleware('auth', 'role');

//Routes pour orders
Route::get('/orders', [OrderController::class, 'index']);
Route::get('/order/{order}', [OrderController::class, 'show']);
Route::post('/order', [OrderController::class, 'store']);
Route::put('/order/{order}', [OrderController::class, 'update']);
Route::delete('/order/{order}', [OrderController::class, 'destroy']);
