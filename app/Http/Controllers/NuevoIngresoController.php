<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Artisaninweb\SoapWrapper\SoapWrapper;

class NuevoIngresoController extends Controller
{
    protected $soapWrapper;
    protected $url;
    protected $mensaje;
    protected $baseUrl;

    public function __construct(SoapWrapper $soapWrapper){

        $this->baseUrl = env('APP_WS_URL');
        $this->url = $this->baseUrl."Propedeutico.asmx?WSDL";
        $this->soapWrapper = $soapWrapper;
        $this->mensaje = [ "error" => "No hay datos disponibles" ];
        
        $this->soapWrapper->add( 'NuevoIngreso', function($service){
            $service
            ->wsdl( $this->url )
            ->trace( TRUE );
        });
    }

    /**
     * @method POST
     * @param Request $request=array<string|int>(maticula,plantel)
     * @return array<string, nom_alumno>
     */
    public function validaMatricula( Request $request ){

        $params    = json_decode($request->getContent(), true);
        $valida    = $this->soapWrapper->call('NuevoIngreso.ValidacionMatricula', [ $params ] );
        $respuesta = $valida->ValidacionMatriculaResult;

        if( empty($respuesta) || empty($respuesta->EntPrope) ) return response()->json($this->mensaje, 400);
        return response()->json( $respuesta->EntPrope );

    }

    /**
     * @method POST
     * @param Request $request=array<string|int>(matricula, plantel, tipoclic)
     * @return object<int>
     * 
     */

    public function addBitacora( Request $request ){

        $params= json_decode($request->getContent(), true);
        $bitacora = $this->soapWrapper->call('NuevoIngreso.BitacoraClic', [ $params ]);
        $respuesta = $bitacora->BitacoraClicResult;

        if( $respuesta == 1 ) return response()->json( $respuesta );
        return response()->json($this->mensaje, 400);
    
    }


}
