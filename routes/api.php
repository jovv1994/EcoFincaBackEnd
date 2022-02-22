<?php

use App\Http\Controllers\DeliveryController;
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

//REGISTRO
Route::post('/register', [UserController::class, 'register']);
//LOGIN
Route::post('/login', [UserController::class, 'authenticate']);
//ENVIO DE CORREO PARA RESETEO DE CONTRASEÑA
Route::post('/forgot-password', [UserController::class, 'sendPasswordResetEmail']);
//ACTUALIZAR CONTRASEÑA
Route::post('/reset-password', [UserController::class, 'confirmPasswordReset']);
//OBTENER TODOS LOS USUARIOS
Route::get('/users', [UserController::class, 'showAll']);

//OBTENER USUARIOS DE CENTRO DE ACOPIO
Route::get('/users', [UserController::class, 'indexCollectionCenters']);

Route::group(['middleware' => ['jwt.verify']], function () {
    //OBTENER INFORMACION DEL USUARIO CON LA SESION ACTIVA
    Route::get('/user', [UserController::class, 'getAuthenticatedUser']);
    //VER ENTREGAS
    Route::get('/deliveries', [DeliveryController::class, 'index']);
    //CREAR ENTREGAS
    Route::post('/deliveries', [DeliveryController::class, 'store']);
    //MOSTRAR UNA ENTREGA
    Route::get('/deliveries/{delivery}', [DeliveryController::class, 'show']);
    //ACTUALIZAR LA ENTREGA
    Route::put('/deliveriesupdate/{delivery}', [DeliveryController::class, 'updateByFarm']);
    //ACTUALIZAR EL ESTADO DE LA ENTREGA POR EL CENTRO DE ACOPIO
    Route::put('/deliveries/{delivery}', [DeliveryController::class, 'updateByCollectionCenter']);
    //ACTUALIZAR LA NOTIFICACIÓN DE RETIRO PARA LA RECOLECCIÓN DE LA ENTREGA POR EL CENTRO DE ACOPIO
    Route::put('/deliveriesupdatenotification/{delivery}', [DeliveryController::class, 'updateNotification']);
    //ACTUALIZAR LA CALIFICACIÓN DE LA ENTREGA POR EL DUEÑO DE FINCA
    Route::put('/deliveriesupdatescore/{delivery}', [DeliveryController::class, 'updateScore']);
    //ACTUALIZAR EL ESTADO DE LA ENTREGA POR EL DUEÑO DE FINCA
    Route::put('/deliveriesupdatestatebyfarm/{delivery}', [DeliveryController::class, 'updateStateByFarm']);
    //ACTUALIZAR LA NOTIFICACIÓN DE LA ENTREGA RECHAZADA POR EL CENTRO DE ACOPIO
    Route::put('/deliveriesupdaterejectedbyacopio/{delivery}', [DeliveryController::class, 'updateRejectedByAcopio']);
    //ACTUALIZAR LA NOTIFICACIÓN DEL COMENTARIO DE LA CALIFICACIÓN MENOR A 5 POR EL DUEÑO DE FINCA
    Route::put('/deliveriesupdatescorecommentbyfarm/{delivery}', [DeliveryController::class, 'updateScoreCommentByFarm']);
    //LOGOUT
    Route::post('/logout', [UserController::class, 'logout']);
});






