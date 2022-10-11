<?php

namespace App\Http\Controllers;
use Artisaninweb\SoapWrapper\SoapWrapper;

use Illuminate\Http\Request;

class OperacionesUnificadasController extends Controller
{
    //
    protected $soapWrapper;

    public function __construct(SoapWrapper $soapWrapper)
    {
        $this->soapWrapper = $soapWrapper;
        
        $this->soapWrapper->add( 'OU', function($service){
    
            $service
            ->wsdl( 'http://comunimex.lat/TestingWSOperacionesUnificadas/OperacionesUnificadas.asmx?WSDL' )
            ->trace( TRUE );
    
        });

    }

    /**
     * 
     * @return array of planteles
     * @method GET 
     */
    
    public function getPlanteles(){
    
        $planteles = $this->soapWrapper->call('OU.ObtenerCatalogoPlanteles');

        if(!$planteles){
            return response()->json(['messagge' => 'error']);
        }else {

            return response()->json( $planteles->ObtenerCatalogoPlantelesResult->PlantelesDTO);
        }
        
    }

    /**
     * function to get niveles with params method
     * @param object of propieties from de WebService
     * 
     */

    public function getNiveles( Request $request){

        //return  $request->toArray('clavePlantel');
        //$plantel =  $request->json('clavePlantel');
        $params= json_decode($request->getContent(), true);

        $niveles = $this->soapWrapper->call('OU.ObtenerCatalogoNivelEscolar', [$params]);

        if(!$niveles){
            return response()->json(['messagge' => 'error']);
        }else {

            return response()->json( $niveles->ObtenerCatalogoNivelEscolarResult->NivelDTO);
        }        

    }

    /**
     * all functions above
     * @param string $plantel, $nivel, $periodo, $carrera
     */

    public function getPeriodos( Request $request){
        $params= json_decode($request->getContent(), true);
        //$params = $request->toArray();

        $periodos = $this->soapWrapper->call('OU.ObtenerCatalogoPeriodoEscolar', [ $params ]);
        
        if(!$periodos){
            return response()->json(['messagge' => 'error']);
        }else {

            return response()->json( $periodos->ObtenerCatalogoPeriodoEscolarResult->PeriodosDTO);
        }


    }

    public function getCarreras( Request $request){
        $params= json_decode($request->getContent(), true);
        //$params = $request->toArray();

        $carreras = $this->soapWrapper->call('OU.ObtenerCatalogoCarreras', [ $params ]);

        if(!$carreras){
            return response()->json(['messagge' => 'error']);
        }else {
            
            return response()->json( $carreras->ObtenerCatalogoCarrerasResult->CarerrasDTO);
        }

    }

    public function getTurnos( Request $request){
        $params= json_decode($request->getContent(), true);
        //$params = $request->toArray();

        $turnos = $this->soapWrapper->call('OU.ObtenerCatalogoTurnos', [ $params ]);

        if(!$turnos){
            return response()->json(['messagge' => 'error']);
        }else {

            return response()->json( $turnos->ObtenerCatalogoTurnosResult->TurnosDTO);
        }

    }

    /**
     * @method POST
     * @param array of prospecto()
     * @return array
     */
    public function addProspecto( Request $request ){
        $params= json_decode($request->getContent(), true);        
        //$params = $request->toArray();
        $prospecto = $this->soapWrapper->call('OU.AgregarProspectoCRM', [ $params ]);

        if( !$prospecto ) {
            return response()->json(['messagge' => 'error']);
        }else {
            return response()->json( $prospecto->AgregarProspectoCRMResult);
        }
    }

    /**
     * @method POST
     * @param object (correoElectronico)
     * @return int
     */

    public function existeProspecto( Request $request ){
        $params= json_decode($request->getContent(), true);
        //$params = $request->toArray();
        $existeProspecto = $this->soapWrapper->call('OU.ExisteProspectoEnCRM', [ $params ]);

        if( !$existeProspecto ) {
            return response()->json(['messagge' => 'error']);
        }else {
            return response()->json( $existeProspecto->ExisteProspectoEnCRMResponse->ExisteProspectoEnCRMResult);
        }
    }

    /**
     * @method GET
     * @return array of estados
     */

    public function getEstados(){

        $estados = $this->soapWrapper->call('OU.ObtenerCatalogoEstados');

        if( !$estados ) {
            return response()->json(['messagge' => 'error']);
        }else {
            return response()->json( $estados->ObtenerCatalogoEstadosResult->EstadosDTO);
        }
    }

    /**
     * @method POST
     * @param object {estado : idEstado}
     * @return array of municipios
     */
    public function getMunicipios( Request $request ){
        $params= json_decode($request->getContent(), true);
        //$params = $request->toArray();

        $municipios = $this->soapWrapper->call('OU.ObtenerCatalogoMunicipios', [ $params ]);

        if( !$municipios ) {
            return response()->json(['messagge' => 'error']);
        }else {
            return response()->json( $municipios->ObtenerCatalogoMunicipiosResult->MunicipiosDTO);
        }
    }

    /**
     * @method POST
     * @param array
     * @return boolean if "OperacionExito"
     * @return string if MensajeError
     */

    public function addProyeccion( Request $request ){
        $params= json_decode($request->getContent(), true);
        //$params = $request->toArray();

        $proyeccion = $this->soapWrapper->call('OU.AgregarProyeccionProfesional', [ $params ]);

        if( !$proyeccion ) {
            return response()->json(['messagge' => 'error']);
        }else {
            return response()->json( $proyeccion->AgregarProyeccionProfesionalResult);
        }
    }

    /**
     * @method POST
     * @param array
     * @return boolean if "OperacionExito"
     * @return string if MensajeError
     */

    public function addDiaUnimex( Request $request ){
        $params= json_decode($request->getContent(), true);
        //$params = $request->toArray();

        $diaU = $this->soapWrapper->call('OU.AgregarDiaUnimex', [ $params ]);

        if( !$diaU ) {
            return response()->json(['messagge' => 'error']);
        }else {
            return response()->json( $diaU->AgregarDiaUnimexResult);
        }
    }

    /**
     * @method POST
     * @param array
     * @return boolean if "OperacionExito"
     * @return string if MensajeError
     */

    public function addProspectacion( Request $request ){
        $params= json_decode($request->getContent(), true);
        //$params = $request->toArray();

        $prospectacion = $this->soapWrapper->call('OU.AgregarProspectacion', [ $params ]);

        if( !$prospectacion ) {
            return response()->json(['messagge' => 'error']);
        }else {
            return response()->json( $prospectacion->AgregarProspectacionResult);
        }
    }

}
