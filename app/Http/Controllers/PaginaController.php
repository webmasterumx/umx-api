<?php

namespace App\Http\Controllers;
use Artisaninweb\SoapWrapper\SoapWrapper;

use Illuminate\Http\Request;

class PaginaController extends Controller
{
    //
    protected $soapWrapper;

    public function __construct(SoapWrapper $soapWrapper)
    {
        $this->soapWrapper = $soapWrapper;
        
        $this->soapWrapper->add( 'Pagina', function($service){
    
            $service
            ->wsdl( 'http://comunimex.lat/TestingWSOperacionesUnificadas/OperacionesUnificadas.asmx?WSDL' )
            ->trace( TRUE );
    
        });

    }

    /**
     * all of this functions
     * @return array of objects
     */
    
    public function getPlanteles(){
    
        $planteles = $this->soapWrapper->call('Pagina.ObtenerCatalogoPlanteles');

        if(!$planteles){
            return response()->json(['messagge' => 'error']);
        }else {

            return $planteles->ObtenerCatalogoPlantelesResult->PlantelesDTO;
        }
        
    }

    /**
     * function to get niveles with params
     * @param object of propieties from de WebService
     * 
     */

    public function getNiveles( Request $request){

        $params = $request->toArray();


        $niveles = $this->soapWrapper->call('Pagina.ObtenerCatalogoNivelEscolar', [ $params ]);

        if(!$niveles){
            return response()->json(['messagge' => 'error']);
        }else {

            return $niveles->ObtenerCatalogoNivelEscolarResult->NivelDTO;
        }
        

    }

    /**
     * all functions above
     * @param string $plantel, $nivel, $periodo, $carrera
     */

    public function getPeriodos( $plantel ){

        $params = array(
            "clavePlantel" => $plantel
        );

        $periodos = $this->soapWrapper->call('Pagina.ObtenerCatalogoPeriodoEscolar', [ $params ]);
        
        if(!$periodos){
            return response()->json(['messagge' => 'error']);
        }else {

            return $periodos->ObtenerCatalogoPeriodoEscolarResult->PeriodosDTO;
        }


    }

    public function getCarreras( $plantel, $nivel, $periodo){

        $params = array(
            "clavePlantel" => $plantel,
            "claveNivel"   => $nivel,
            "clavePeriodo" => $periodo
        );

        $carreras = $this->soapWrapper->call('Pagina.ObtenerCatalogoCarreras', [ $params ]);

        if(!$carreras){
            return response()->json(['messagge' => 'error']);
        }else {
            
            return $carreras->ObtenerCatalogoCarrerasResult->CarerrasDTO;
        }

    }

    public function getTurnos( $plantel, $nivel, $periodo, $carrera ){

        $params = array(
            "clavePlantel" => $plantel,
            "claveNivel"   => $nivel,
            "clavePeriodo" => $periodo,
            "claveCarrera" => $carrera
        );

        $turnos = $this->soapWrapper->call('Pagina.ObtenerCatalogoTurnos', [ $params ]);

        if(!$turnos){
            return response()->json(['messagge' => 'error']);
        }else {

            return $turnos->ObtenerCatalogoTurnosResult->TurnosDTO;
        }

    }




}
