<?php

namespace App\Http\Controllers;

use Artisaninweb\SoapWrapper\SoapWrapper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class DiaUnimexController extends Controller
{
    protected $soapWrapper;
    protected $url;
    protected $mensaje;

    /**
     * Class constructor.
     */
    public function __construct(SoapWrapper $soapWrapper)
    {
        App::environment('local') ? 
            $this->url = "http://comunimex.lat/TestingWSOperacionesUnificadas/OperacionesUnificadas.asmx?WSDL" :
            $this->url = "http://comunimex.lat/WSOperacionesUnificadas/OperacionesUnificadas.asmx?WSDL";

        $this->soapWrapper = $soapWrapper;
        $this->mensaje = [
            "error" => "No hay datos disponibles"
        ];
        
        $this->soapWrapper->add( 'DU', function($service){
    
            $service->wsdl( $this->url )
                    ->trace( TRUE );
        });
    }

    /**
     * @method POST
     */
}
