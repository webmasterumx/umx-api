<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SoapConnectModel;
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
        $valida = $this->soapWrapper->call('NuevoIngreso.ValidacionMatricula', [ $params ]);

        return $valida;

        
    }


}
