<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artisaninweb\SoapWrapper\SoapWrapper;

class NuevoIngresoController extends Controller
{

    protected $soapWrapper;

    public function __construct(SoapWrapper $soapWrapper){

        $this->soapWrapper = $soapWrapper;
        
        $this->soapWrapper->add( 'NuevoIngreso', function($service){
    
            $service
            ->wsdl( 'http://comunimex.lat/TestingWSOperacionesUnificadas/Propedeutico.asmx?WSDL' )
            ->trace( TRUE );
    
        });
    }

    public function validaMatricula( Request $request ){

        $params = $request->toArray();

        $valida = $this->soapWrapper->call('NuevoIngreso.ValidacionMatricula', [ $params ] );
        return $valida->ValidacionMatriculaResult->EntPrope;

    }

    /**
     * in this function
     * @return object
     * because the ws return a simple int
     */

    public function addBitacora( Request $request ){

        $params = $request->toArray();

        $bitacora = $this->soapWrapper->call('NuevoIngreso.BitacoraClic', [ $params ]);
        $resultado = $bitacora->BitacoraClicResult;

        return response()->json([
            "resultado" => $resultado
        ]);
    }


}
