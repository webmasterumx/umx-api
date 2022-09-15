<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\OfertaController;
use App\Http\Controllers\CalculadoraController;
use App\Http\Controllers\NuevoIngresoController;
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
/**
 * ex1:
 * route with params
 * Route::post('pagina/niveles/{plantel}', 'getNiveles')->where('plantel', '[2-5]+' );
 * 
 */

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('cors')
    ->controller(OfertaController::class)
    ->group( function(){
        // route with Reques method
        Route::get('oferta/planteles', 'getPlanteles');
        Route::post('oferta/niveles', 'getNiveles');
        Route::post('oferta/periodos', 'getPeriodos');
        Route::post('oferta/carreras', 'getCarreras');
        Route::post('oferta/turnos', 'getTurnos');
});

Route::controller( CalculadoraController::class )->group( function(){

    /**
     * the method for this route doesn't work
     * Route::post('calculadora/promedios/{plantel}', 'getPromedios')->whereNumber('plantel');
     */

    Route::post('calculadora/horarios', 'getHorarios');
    Route::post('calculadora/detalle-horario', 'getDetalleHorarios');
    Route::post('calculadora/actualiza', 'updateProspectos');

});

Route::controller( NuevoIngresoController::class )->group( function(){

    Route::post('ingreso/valida', 'validaMatricula');
    Route::post('ingreso/bitacora', 'addBitacora');



});


