<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artisaninweb\SoapWrapper\SoapWrapper;
use Illuminate\Support\Facades\App;

class SideTrackController extends Controller
{
    protected $soapWrapper;
    protected $url;
    protected $mensaje;

    public function __construct(SoapWrapper $soapWrapper)
    {
        App::environment('local') ? 
            $this->url = "http://comunimex.lat/TestingWSOperacionesUnificadas/SideTrack.asmx?WSDL" :
            $this->url = "http://comunimex.lat/WSOperacionesUnificadas/SideTrack.asmx?WSDL";

        $this->soapWrapper = $soapWrapper;
        $this->mensaje = [
            "error" => "No hay datos disponibles"
        ];
        
        $this->soapWrapper->add( 'SideTrack', function($service){
            $service->wsdl( $this->url )->trace( TRUE );
        });
    }

    /**
     * @method POST
     * @param Request $request=array<string|int>,same as OperacionesUnificadas
     * @param $clavemodo=2 if plantel=5 && $clavemodo=0 if plantel=2,3,4
     * @return object
     */

    public function getCarreras( Request $request){

        $params= json_decode($request->getContent(), true);
        $carreras = $this->soapWrapper->call('SideTrack.ObtenerCatalogoCarreras', [ $params ]);
        $respuesta = $carreras->ObtenerCatalogoCarrerasResult;

        if( empty($respuesta) || empty($respuesta->CarerrasDTO) ) return response()->json($this->mensaje, 400);
        return response()->json( $respuesta->CarerrasDTO);

    }

    public function getTurnos( Request $request){

        $params= json_decode($request->getContent(), true);
        $turnos = $this->soapWrapper->call('SideTrack.ObtenerCatalogoTurnos', [ $params ]);
        $respuesta = $turnos->ObtenerCatalogoTurnosResult;

        if( empty($respuesta) || empty($respuesta->TurnosDTO) ) return response()->json($this->mensaje, 400);
        return response()->json( $respuesta->TurnosDTO);

    }


}