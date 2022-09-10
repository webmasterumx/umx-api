<?php

namespace App\Http\Controllers;

use Artisaninweb\SoapWrapper\SoapWrapper;
use Illuminate\Http\Request;

class CalculadoraController extends Controller
{
    protected $soapWrapper;

    public function __construct(SoapWrapper $soapWrapper)
    {
        $this->soapWrapper = $soapWrapper;
        
        $this->soapWrapper->add( 'Calculadora', function($service){
    
            $service
            ->wsdl( 'http://comunimex.lat/TestingWSOperacionesUnificadas/calculadoracuotas.asmx?WSDL' )
            ->trace( TRUE );
    
        });

    }

    public function getPromedios( $plantel ){

        $promedios = $this->soapWrapper->call( 'Calculadora.ObtenerCatalogoPromedios');

        if( !$promedios ){
            return response()->json(['messagge' => 'error']);
        }else {

            return $promedios->ObtenerCatalogoPromediosResult->PromediosDTO;
        }

    }
}
