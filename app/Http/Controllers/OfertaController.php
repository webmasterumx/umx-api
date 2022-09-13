<?php

namespace App\Http\Controllers;
use Artisaninweb\SoapWrapper\SoapWrapper;

use Illuminate\Http\Request;

class OfertaController extends Controller
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
     * function to get niveles with params method
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

    public function getPeriodos( Request $request){

        $params = $request->toArray();

        $periodos = $this->soapWrapper->call('Pagina.ObtenerCatalogoPeriodoEscolar', [ $params ]);
        
        if(!$periodos){
            return response()->json(['messagge' => 'error']);
        }else {

            return $periodos->ObtenerCatalogoPeriodoEscolarResult->PeriodosDTO;
        }


    }

    public function getCarreras( Request $request){

        $params = $request->toArray();

        $carreras = $this->soapWrapper->call('Pagina.ObtenerCatalogoCarreras', [ $params ]);

        if(!$carreras){
            return response()->json(['messagge' => 'error']);
        }else {
            
            return $carreras->ObtenerCatalogoCarrerasResult->CarerrasDTO;
        }

    }

    public function getTurnos( Request $request){

        $params = $request->toArray();

        $turnos = $this->soapWrapper->call('Pagina.ObtenerCatalogoTurnos', [ $params ]);

        if(!$turnos){
            return response()->json(['messagge' => 'error']);
        }else {

            return $turnos->ObtenerCatalogoTurnosResult->TurnosDTO;
        }

    }




}
