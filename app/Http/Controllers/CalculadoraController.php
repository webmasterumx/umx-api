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

        $params = $request->toArray();

        $horarios = $this->soapWrapper->call('Calculadora.ObtenerHorariosBecas', [ $params ]);
        return $horarios->ObtenerHorariosBecasResult->HorariosBecasDTO;

    }
    /**
     * @method POST
     * @param Request $request=array<string|int>(PlantelId,claveCarrera,claveTurno,claveNivel,clavePeriodo,claveBeca,egresado)
     * @return array<object, DetalleHorarioBecaDTO>
     * 
     */

    public function getDetalleHorarios( Request $request ){

        $params = $request->toArray();

        $detalleHorario = $this->soapWrapper->call('Calculadora.ObtenerDetalleHorarioBeca', [ $params ]);
        return $detalleHorario->ObtenerDetalleHorarioBecaResult->DetalleHorarioBecaDTO;

    }
    /**
     * @method POST
     * @param Request $request=array<string|int>(clavePeriodo,clavePlantel,claveNivel,claveCarrera,claveTurno,folioCRM)
     * @return array<object, ActualizaProspectoDTO>
     * 
     */

    public function updateProspectos( Request $request ){
        
        $params = $request->toArray();

        $actualizaProspecto = $this->soapWrapper->call('Calculadora.ActualizaProspecto', [ $params ]);
        return $actualizaProspecto->ActualizaProspectoResult->ActualizaProspectoDTO;
    }
}
