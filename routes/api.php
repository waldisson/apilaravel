<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EnderecoController;
use App\Http\Controllers\ProductsController;



Route::get('/ping', function(){
    return ['pong' => true];
});

Route::get('/401',[AuthController::class, 'unauthorized'])->name('login');

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

Route::middleware('auth:api')->group(function(){
    Route::post('/auth/validate', [AuthController::class, 'validateToken']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Usuarios
    Route::post('/user',[AuthController::class, 'create']);
    Route::get('/users',[UserController::class, 'read']);
    
    // Endereços
    Route::post('/address',[EnderecoController::class, 'createAddress']);
    Route::get('/address/ver/{id}',[EnderecoController::class, 'getAddress']);
    Route::delete('/address/{id}',[EnderecoController::class, 'removeAddress']);
    Route::put('/address/{id}',[EnderecoController::class, 'putAddress']);

    // Produtos
    Route::get('/products',[ProductsController::class, 'products']);
    Route::get('/products/{id}',[ProductsController::class, 'readProduct']);
    Route::post('/products',[ProductsController::class, 'createproduct']);
    Route::post('/product/photo',[ProductsController::class, 'createImagproduct']);
    Route::put('/product/{id}',[ProductsController::class, 'updateproduct']);
    Route::delete('/products/{id}',[ProductsController::class, 'delproduct']);
    

});



