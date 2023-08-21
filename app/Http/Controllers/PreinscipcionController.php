<?php

namespace App\Http\Controllers;

use Artisaninweb\SoapWrapper\SoapWrapper;
use Illuminate\Http\Request;

class PreinscipcionController extends Controller{
    
    protected $soapWrapper;
    protected $url;
    protected $mensaje;
    protected $baseUrl;

    public function __construct(SoapWrapper $soapWrapper){

        $this->baseUrl = config('app.ws_url');
        $this->url = $this->baseUrl."preinscripcionenlinea.asmx?WSDL";
        $this->soapWrapper = $soapWrapper;
        $this->mensaje = [ "error" => "No hay datos disponibles" ];
        
        $this->soapWrapper->add( 'Preinscripcion', function($service){
            $service
            ->wsdl( $this->url )
            ->trace( TRUE );
        });

    }

    /**
     * @method POST
     * @param Request $request=array<string|int>(clavePlantel,clavePeriodo,claveNivel,claveTurno)
     * @return array of promociones
     * 
     */
    public function getPromociones( Request $request ){

        $params      = json_decode($request->getContent(), true);
        $promociones = $this->soapWrapper->call('Preinscripcion.ObtenerImportePromocionesPreinscripcion', [ $params ]);
        $respuesta   = $promociones->ObtenerImportePromocionesPreinscripcionResult;

        if( empty($respuesta) ) return response()->json($this->mensaje, 400);
        return response()->json( $respuesta );

    }
    
    /**
     * @method POST
     * @param array of (prospecto)
     * @return array of registro
     * 
     */
    public function registraProspecto( Request $request ){

        $params    = json_decode($request->getContent(), true);
        $prospecto = $this->soapWrapper->call('Preinscripcion.RegistraProspectoCRMDesdePreinscripcionEnLinea', [ $params ]);
        $respuesta = $prospecto->RegistraProspectoCRMDesdePreinscripcionEnLineaResult;

        if( empty($respuesta) ) return response()->json($this->mensaje, 400);
        return response()->json( $respuesta );

    }



}
