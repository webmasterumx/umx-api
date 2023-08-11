<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\OperacionesUnificadasController;
use App\Http\Controllers\CalculadoraController;
use App\Http\Controllers\DiaUnimexController;
use App\Http\Controllers\NuevoIngresoController;
use App\Http\Controllers\PreinscipcionController;
use App\Http\Controllers\SideTrackController;
use App\Http\Controllers\GraduacionesController;
use App\Http\Controllers\KontuxController;

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

//Route::middleware('cors')
Route::controller(OperacionesUnificadasController::class)->group(function(){
    
    // route with Reques method
    Route::get('oferta/planteles', 'getPlanteles');
    Route::any('oferta/niveles', 'getNiveles');
    Route::post('oferta/periodos', 'getPeriodos');
    Route::post('oferta/carreras', 'getCarreras');
    Route::post('oferta/turnos', 'getTurnos');
    Route::post('agrega-prospecto', 'addProspecto');
    Route::post('verifica-prospecto', 'existeProspecto');
    Route::get('estados', 'getEstados');
    Route::post('municipios', 'getMunicipios');
    Route::post('proyeccion', 'addProyeccion');
    Route::post('dia-unimex', 'addDiaUnimex');
    Route::post('prospectacion', 'addProspectacion');
    Route::get('dia-unimex/escuelas', 'getEscuelaOrigen');
    Route::post('dia-unimex/guarda-prospecto', 'addEscuelaOrigen');
    Route::post('dia-unimex/carreras', 'getCarrerasDiaUnimex' );    
    Route::post('dia-unimex/turnos', 'getHorariosDiaUnimex');
    

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

Route::controller( PreinscipcionController::class )->group( function(){

        Route::post('preinscripcion/promociones', 'getPromociones');
        Route::post('preinscripcion/agrega-prospecto', 'registraProspecto');
        
});

Route::controller( SideTrackController::class )->group( function(){

    Route::post('side/carreras', 'getCarreras');
    Route::post('side/turnos', 'getTurnos');

});

Route::controller( GraduacionesController::class )->group( function(){

    Route::post('graduaciones/valida-matricula',  'validaMatricula');
    Route::post('graduaciones/guarda', 'guardaGraduado' );

});

Route::controller( KontuxController::class )->group( function(){
    Route::get('kontux/planteles', 'getPlanteles');
    Route::get('kontux/niveles', 'getNiveles');
    Route::post('kontux/carreras', 'getCarreras');
    Route::post('kontux/turnos', 'getTurnos');
    Route::post('kontux/valida-celular', 'validaCelular');
    Route::post('kontux/obtener-ubicacion-plantel', 'getUbicacionPlantel');
    Route::post('kontux/obtener-documentos', 'getDocumentos');
    Route::post('kontux/obtener-documentos-nivel', 'getDocumentosNivel');
});