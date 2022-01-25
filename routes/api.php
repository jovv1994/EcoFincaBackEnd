<?php

use App\Http\Controllers\CantonController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\ParroquiaController;
use App\Http\Controllers\ProvinciaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// REGISTRO Y LOGIN
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'authenticate']);

// RUTA PARA OBTENER USUARIOS DE CENTRO DE ACOPIO
Route::get('/users', [UserController::class, 'indexCollectionCenters']);

Route::group(['middleware' => ['jwt.verify']], function () {
    //OBTENER INFORMACION DEL USUARIO CON LA SESION ACTIVA
    Route::get('/user', [UserController::class, 'getAuthenticatedUser']);
    // VER ENTREGAS
    Route::get('deliveries', [DeliveryController::class, 'index']);
    // CREAR ENTREGAS
    Route::post('deliveries', [DeliveryController::class, 'store']);
    // MOSTRAR UNA ENTREGA => funciona
    Route::get('deliveries/{delivery}', [DeliveryController::class, 'show']);
    // ACTUALIZAR ENTREGA POR FINCA => pendiente revisar
    Route::put('deliveriesupdate/{delivery}', [DeliveryController::class, 'updateByFarm']);
    // ACTUALIZAR ENTREGA POR ACOPIO => funciona
    Route::put('deliveries/{delivery}', [DeliveryController::class, 'updateByCollectionCenter']);
    // LOGOUT

    Route::post('/logout', [UserController::class, 'logout']);


    // pendiente de revisar

    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::get('users/{user}/image', [UserController::class, 'image']);
    Route::get('deliveries/{delivery}/image', [DeliveryController::class, 'image']);
});






