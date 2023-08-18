<?php

namespace App\Http\Controllers;

use Artisaninweb\SoapWrapper\SoapWrapper;
use Illuminate\Http\Request;

class ZapierController extends Controller
{
    protected $soapWrapper;
    protected $url;
    protected $mensaje;
    protected $baseUrl;

    public function __construct( SoapWrapper $soapWrapper) {

        $this->baseUrl = config('ws.url');
        $this->url = $this->baseUrl."Kontux.asmx?WSDL";
        $this->soapWrapper = $soapWrapper;
        $this->mensaje = [
            "error" => "No hay datos disponibles"
        ];
        $this->soapWrapper->add('KNTX', function( $service ){
            $service->wsdl( $this->url)
                    ->trace( TRUE );
        });

    }

    public function facebookRegister( Request $request ){

        $params = $request->all();

        if( empty($params) ) return response()->json($this->mensaje, 400);
        return response()->json( $params );

    }

}
