<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controllers\productController;


// Rutas para Products

Route::get('/products', [productController::class, 'getAllProducts']);

Route::get('/products/{id}', [productController::class, 'getProductByID']);

Route::post('/products', [productController::class, 'postProduct']);

Route::delete('/products/{id}', [productController::class, 'deleteProductbyID']);

Route::put('/products/{id}', [productController::class, 'updateProduct']);

Route::patch('/products/{id}', [productController::class, 'updatePartialProduct']);


// Rutas para Users
Route::get('/users', function () {
    return "Obteniendo todos los usuarios";
}); //[UserController::class, 'getAllUsers']

Route::get('/users/{id}', function ($id) {
    return "Obteniendo el usuario con id: $id";
}); //[UserController::class, 'getUserByID']

Route::post('/users', function (Request $request) {
    return "Creando un nuevo usuario";
}); //[UserController::class, 'postUser']

Route::delete('/users/{id}', function ($id) {
    return "Eliminando el usuario con id: $id";
}); //[UserController::class, 'deleteUserByID']

Route::put('/users/{id}', function ($id) {
    return "Actualizando el usuario con id: $id";
}); //[UserController::class, 'updateUser']

Route::patch('/users/{id}', function ($id) {
    return "Actualizando parcialmente el usuario con id: $id";
}); //[UserController::class, 'updatePartialUser']