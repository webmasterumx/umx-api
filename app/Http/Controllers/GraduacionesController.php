<?php

namespace App\Http\Controllers;
use Artisaninweb\SoapWrapper\SoapWrapper;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;

class GraduacionesController extends Controller
{
    protected $soapWrapper;
    protected $url;
    protected $mensaje;

    public function __construct(SoapWrapper $soapWrapper)
    {
        App::environment('local') ? 
            $this->url = "http://comunimex.lat/TestingWSOperacionesUnificadas/Graduaciones.asmx?WSDL" :
            $this->url = "http://comunimex.lat/WSOperacionesUnificadas/Graduaciones.asmx?WSDL";

        $this->soapWrapper = $soapWrapper;
        $this->mensaje = [
            "error" => "No hay datos disponibles"
        ];
        
        $this->soapWrapper->add( 'Graduaciones', function($service){
    
            $service->wsdl( $this->url )
                    ->trace( TRUE );
        });

    }

    /**
     * @return array<id_empresa,nombre,Alta>
     * @param Request<array>matricula|clave_per
     */

    public function validaMatricula( Request $request ){

        $params    = json_decode($request->getContent(), true);
        $valida    = $this->soapWrapper->call('Graduaciones.ValidaMatriculaConfirmado', [ $params ]);
        $respuesta = $valida->ValidaMatriculaConfirmadoResult;

        if( empty($respuesta) || empty($respuesta->Confirmados) ) return response()->json($this->mensaje, 400);
        return response()->json( $respuesta->Confirmados );

    }


    /**
     * @param Request<array>matricula,celular,correo,clave_per
     * @return array<0|1>
     */

    public function guardaGraduado( Request $request ){

        $params= json_decode($request->getContent(), true);
        $guarda = $this->soapWrapper->call('Graduaciones.IngresaMatriculaConfirmado', [ $params ]);
        $respuesta = $guarda->IngresaMatriculaConfirmadoResult;

        if( $respuesta == 1 ) return response()->json( ["mensaje" => $respuesta] );
        return response()->json(["mensanje" => "existe"]);

    }

}
