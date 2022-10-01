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

    /**
     * @method POST
     * @param Request $request=array<string|int>(maticula,plantel)
     * @return array<string, nom_alumno>
     */
    public function validaMatricula( Request $request ){

        $params = $request->toArray();

        $valida = $this->soapWrapper->call('NuevoIngreso.ValidacionMatricula', [ $params ] );
        return $valida->ValidacionMatriculaResult->EntPrope;

    }

    /**
     * @method POST
     * @param Request $request=array<string|int>(matricula, plantel, tipoclic)
     * @return object<int>
     * 
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
