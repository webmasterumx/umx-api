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

        $this->baseUrl = config('app.ws_url');
        $this->url = $this->baseUrl."Adview.asmx?WSDL";
        $this->soapWrapper = $soapWrapper;
        $this->mensaje = [
            "error" => "No hay datos disponibles"
        ];
        $this->soapWrapper->add('ADVW', function( $service ){
            $service->wsdl( $this->url)
                    ->trace( TRUE );
        });

    }

    /**
     * Guarda los prospecto provenientes de los formularios de Facebook
     * @param array|object Contiene los datos desde zapier
     * @return object respuesta
     */

    public function facebookRegister( Request $request ){

        $params = $request->all();
        $addFacebookProspecto = $this->soapWrapper->call('ADVW.AgregaProspectoCrmFacebook', [$params]);
        $respuesta = $addFacebookProspecto->AgregaProspectoCrmFacebookResult;

        if( empty($respuesta) || $respuesta == 0  ) return response()->json($this->mensaje, 400);
        return response()->json([
            'respuesta' => $respuesta
        ]);

    }

}
