<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\PaginaController;
use App\Http\Controllers\CalculadoraController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(PaginaController::class)->group( function(){

    Route::get('pagina/planteles', 'getPlanteles');
    /**
     * reoute with params
     * Route::post('pagina/niveles/{plantel}', 'getNiveles')->where('plantel', '[2-5]+' );
     * 
     */
    // route with Reques method
    Route::post('pagina/niveles', 'getNiveles');
    Route::post('pagina/periodos/{plantel}', 'getPeriodos')->where('plantel', '[2-5]+' );
    Route::post('pagina/carreras/{plantel}/{nivel}/{periodo}', 'getCarreras')->where([
        'plantel' => '[2-5]+',
        'nivel'   => '[1-3]+',
    ])->whereNumber('perido');

    Route::post('pagina/turnos/{plantel}/{nivel}/{periodo}/{carrera}', 'getTurnos')->where([
        'plantel' => '[2-5]+',
        'nivel'   => '[1-3]+',
    ])->whereNumber('perido')->whereNumber('carrera');

});

Route::controller( CalculadoraController::class )->group( function(){

    /**
     * the method for this route doesn't work
     * Route::post('calculadora/promedios/{plantel}', 'getPromedios')->whereNumber('plantel');
     */

    Route::post('calculadora/horarios', 'getHorarios');
    Route::post('calculadora/detalle-horario', 'getDetalleHorarios');

    

    

});


