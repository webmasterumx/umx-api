<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\PaginaController;
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
    Route::post('pagina/niveles/{plantel}', 'getNiveles')->where('plantel', '[2-5]+' );
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


