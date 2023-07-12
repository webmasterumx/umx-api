<?php

namespace App\Http\Controllers;
use Artisaninweb\SoapWrapper\SoapWrapper;

use Illuminate\Http\Request;

class KontuxController extends Controller
{
    protected $soapWrapper;
    protected $url;
    protected $mensaje;

    public function __construct( SoapWrapper $soapWrapper) {
        
        /**
         * only use this for testing, then create the function with local variable
         */

        $this->url = "http://comunimex.lat/TestingWSOperacionesUnificadas/Kontux.asmx?WSDL";
        $this->soapWrapper = $soapWrapper;
        $this->mensaje = [
            "error" => "No hay datos disponibles"
        ];
        $this->soapWrapper->add('KNTX', function( $service ){
            $service->wsdl( $this->url)
                    ->trace( TRUE );
        });

    }

    /**
     * 
     * @return array of planteles
     * @method GET 
     */
    
     public function getPlanteles(){
    
        $planteles = $this->soapWrapper->call('KNTX.TraePlantel');
        $respuesta = $planteles->TraePlantelResult;

        if( empty($respuesta) || empty($respuesta->PlantelesDTO) ) return response()->json($this->mensaje, 400);
        return response()->json( $respuesta->PlantelesDTO );    
    }

    /**
     * function to get niveles with params method
     * @param object of propieties from de WebService
     * 
     */

    public function getNiveles( Request $request){

        $params= json_decode($request->getContent(), true);
        $niveles = $this->soapWrapper->call('KNTX.TraeNivel', [$params]);
        $respuesta = $niveles->TraeNivelResult;

        if( empty($respuesta) || empty($respuesta->NivelDTO) ) return response()->json($this->mensaje, 400);
        return response()->json( $respuesta->NivelDTO);

    }

    /**
     * all functions above
     * @param string $plantel, $nivel, $periodo, $carrera
     */

    public function getPeriodos( Request $request){
        
        $params = json_decode($request->getContent(), true);
        $periodos = $this->soapWrapper->call('KNTX.ObtenerCatalogoPeriodoEscolar', [ $params ]);
        $respuesta = $periodos->ObtenerCatalogoPeriodoEscolarResult;

        if( empty($respuesta) || empty($respuesta->PeriodosDTO) ) return response()->json($this->mensaje, 400);
        return response()->json( $respuesta->PeriodosDTO );

    }

    public function getCarreras( Request $request){

        $params= json_decode($request->getContent(), true);
        $carreras = $this->soapWrapper->call('KNTX.TraeCarrera', [ $params ]);
        $respuesta = $carreras->TraeCarreraResult;

        if( empty($respuesta) || empty($respuesta->CarerrasDTO) ) return response()->json($this->mensaje, 400);
        return response()->json( $respuesta->CarerrasDTO);

    }

    public function getTurnos( Request $request){

        $params= json_decode($request->getContent(), true);
        $turnos = $this->soapWrapper->call('KNTX.TraesTurnos', [ $params ]);
        $respuesta = $turnos->TraesTurnosResult;

        if( empty($respuesta) || empty($respuesta->TurnosDTO) ) return response()->json($this->mensaje, 400);
        return response()->json( $respuesta->TurnosDTO);

    }

    public function validaCelular( Request $request ){
        $params = json_decode( $request->getContent(), true);
        $validacion = $this->soapWrapper->call('KNTX.ValidaCelular', [$params]);
        $respuesta = $validacion->ValidaCelularResult;

        if( empty($respuesta) || empty($respuesta->ProspectoKontux) ) return response()->json($this->mensaje, 400);
        return response()->json( $respuesta->ProspectoKontux);
    }
}
