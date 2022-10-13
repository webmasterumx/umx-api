<?php

namespace App\Http\Controllers;

use Artisaninweb\SoapWrapper\SoapWrapper;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;

class CalculadoraController extends Controller
{
    protected $soapWrapper;
    protected $url;
    protected $mensaje;

    public function __construct(SoapWrapper $soapWrapper)
    {
        App::environment('local') ? 
            $this->url = "http://comunimex.lat/TestingWSOperacionesUnificadas/calculadoracuotas.asmx?WSDL" :
            $this->url = "http://comunimex.lat/WSOperacionesUnificadas/calculadoracuotas.asmx?WSDL";

        $this->soapWrapper = $soapWrapper;
        $this->mensaje = [ "error" => "No hay datos disponibles" ];
        
        $this->soapWrapper->add( 'Calculadora', function($service){
    
            $service
            ->wsdl( $this->url )
            ->trace( TRUE );
    
        });

    }

    /**
     * --El metodo obtener catalogo promedios no funciona actualmente 12-09-2022.
     * --garbageCode
     */
    /*
    public function getPromedios( $plantel ){

        $params = array(
            "PlantelId" => $plantel
        );

        $promedios = $this->soapWrapper->call('Calculadora.ObtenerCatalogoPromedios', [ $params ]);

        if( !$promedios ){
            return response()->json(['messagge' => 'error']);
        }else {

            //return $promedios->ObtenerCatalogoPromediosResult->PromediosDTO;    
            return $promedios;
        }

    }
    */

    
    /**
     * @method POST
     * @param Request $request=array<string|int>(PlantelId,clavePeriodo,claveNivel,claveCarrera, promedio=0)
     * @return array<object, HorariosBecasDTO>
     * 
     */
    public function getHorarios( Request $request ){

        $params    = json_decode($request->getContent(), true);
        $horarios  = $this->soapWrapper->call('Calculadora.ObtenerHorariosBecas', [ $params ]);
        $respuesta = $horarios->ObtenerHorariosBecasResult;

        if( empty($respuesta) || empty($respuesta->HorariosBecasDTO) ) return response()->json($this->mensaje, 400);
        return response()->json( $respuesta->HorariosBecasDTO );

    }
    /**
     * @method POST
     * @param Request $request=array<string|int>(PlantelId,claveCarrera,claveTurno,claveNivel,clavePeriodo,claveBeca,egresado)
     * @return array<object, DetalleHorarioBecaDTO>
     * 
     */

    public function getDetalleHorarios( Request $request ){

        $params         = json_decode($request->getContent(), true);
        $detalleHorario = $this->soapWrapper->call('Calculadora.ObtenerDetalleHorarioBeca', [ $params ]);
        $respuesta      = $detalleHorario->ObtenerDetalleHorarioBecaResult;

        if( empty($respuesta) || empty($respuesta->DetalleHorarioBecaDTO) ) return response()->json($this->mensaje, 400);
        return response()->json( $respuesta->DetalleHorarioBecaDTO );

    }
    /**
     * @method POST
     * @param Request $request=array<string|int>(clavePeriodo,clavePlantel,claveNivel,claveCarrera,claveTurno,folioCRM)
     * @return array<object, ActualizaProspectoDTO>
     * 
     */

    public function updateProspectos( Request $request ){

        $params             = json_decode($request->getContent(), true);
        $actualizaProspecto = $this->soapWrapper->call('Calculadora.ActualizaProspecto', [ $params ]);
        $respuesta          =  $actualizaProspecto->ActualizaProspectoResult;

        if( empty($respuesta) || empty($respuesta->ActualizaProspectoDTO) ) return response()->json($this->mensaje, 400);
        return response()->json( $respuesta->ActualizaProspectoDTO );

    }
}
