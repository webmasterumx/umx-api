<?php

namespace App\Http\Controllers;
use Artisaninweb\SoapWrapper\SoapWrapper;
use Illuminate\Http\Request;

class PreinscipcionController extends Controller{
    
    protected $soapWrapper;

    public function __construct(SoapWrapper $soapWrapper){
        $this->soapWrapper = $soapWrapper;
        
        $this->soapWrapper->add( 'Preinscripcion', function($service){
    
            $service
            ->wsdl( 'http://comunimex.lat/TestingWSOperacionesUnificadas/preinscripcionenlinea.asmx?WSDL' )
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

        $params= json_decode($request->getContent(), true);
        //$params = $request->toArray();
        $promociones = $this->soapWrapper->call('Preinscripcion.ObtenerImportePromocionesPreinscripcion', [ $params ]);

        if( !$promociones ){
            return response()->json(['messagge' => 'error']);
        }else{

            return response()->json( $promociones->ObtenerImportePromocionesPreinscripcionResult );
        }

    }
    
    /**
     * @method POST
     * @param array of (prospecto)
     * @return array of registro
     * 
     */
    public function registraProspecto( Request $request ){

        $params= json_decode($request->getContent(), true);
        //$params = $request->toArray();
        $prospecto = $this->soapWrapper->call('Preinscripcion.RegistraProspectoCRMDesdePreinscripcionEnLinea', [ $params ]);

        if( !$prospecto ) {
            return response()->json(['messagge' => 'error']);
        }else {
            return response()->json( $prospecto->RegistraProspectoCRMDesdePreinscripcionEnLineaResult );
        }
    }



}
