<?php

namespace App\Http\Controllers;
use Artisaninweb\SoapWrapper\SoapWrapper;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;

class OperacionesUnificadasController extends Controller
{
    //
    protected $soapWrapper;
    protected $url;
    protected $mensaje;
    protected $baseURL;

    public function __construct(SoapWrapper $soapWrapper)
    {
        $this->baseURL = config('app.ws_url');
        $this->url = $this->baseURL."OperacionesUnificadas.asmx?WSDL";
        $this->soapWrapper = $soapWrapper;
        $this->mensaje = [
            "error" => "No hay datos disponibles"
        ];
        
        $this->soapWrapper->add( 'OU', function($service){
    
            $service->wsdl( $this->url )
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
        $respuesta = $planteles->ObtenerCatalogoPlantelesResult;

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
        $niveles = $this->soapWrapper->call('OU.ObtenerCatalogoNivelEscolar', [$params]);
        $respuesta = $niveles->ObtenerCatalogoNivelEscolarResult;

        if( empty($respuesta) || empty($respuesta->NivelDTO) ) return response()->json($this->mensaje, 400);
        return response()->json( $respuesta->NivelDTO);

    }

    /**
     * all functions above
     * @param string $plantel, $nivel, $periodo, $carrera
     */

    public function getPeriodos( Request $request){
        
        $params = json_decode($request->getContent(), true);
        $periodos = $this->soapWrapper->call('OU.ObtenerCatalogoPeriodoEscolar', [ $params ]);
        $respuesta = $periodos->ObtenerCatalogoPeriodoEscolarResult;

        if( empty($respuesta) || empty($respuesta->PeriodosDTO) ) return response()->json($this->mensaje, 400);
        return response()->json( $respuesta->PeriodosDTO );

    }

    public function getCarreras( Request $request){

        $params= json_decode($request->getContent(), true);
        $carreras = $this->soapWrapper->call('OU.ObtenerCatalogoCarreras', [ $params ]);
        $respuesta = $carreras->ObtenerCatalogoCarrerasResult;

        if( empty($respuesta) || empty($respuesta->CarerrasDTO) ) return response()->json($this->mensaje, 400);
        return response()->json( $respuesta->CarerrasDTO);

    }

    public function getTurnos( Request $request){

        $params= json_decode($request->getContent(), true);
        $turnos = $this->soapWrapper->call('OU.ObtenerCatalogoTurnos', [ $params ]);
        $respuesta = $turnos->ObtenerCatalogoTurnosResult;

        if( empty($respuesta) || empty($respuesta->TurnosDTO) ) return response()->json($this->mensaje, 400);
        return response()->json( $respuesta->TurnosDTO);

    }

    /**
     * @method POST
     * @param array of prospecto()
     * @return array
     */
    public function addProspecto( Request $request ){

        $params= json_decode($request->getContent(), true);        
        $prospecto = $this->soapWrapper->call('OU.AgregarProspectoCRM', [ $params ]);
        $respuesta= $prospecto->AgregarProspectoCRMResult;

        if( empty($respuesta) ) return response()->json($this->mensaje, 400);
        return response()->json( $respuesta );

    }

    /**
     * @method POST
     * @param object (correoElectronico)
     * @return int
     */

    public function existeProspecto( Request $request ){

        $params= json_decode($request->getContent(), true);
        $existeProspecto = $this->soapWrapper->call('OU.ExisteProspectoEnCRM', [ $params ]);
        $respuesta = $existeProspecto->ExisteProspectoEnCRMResult;

        if( $respuesta == 1 ) return response()->json( $respuesta );
        return response()->json($this->mensaje, 400);

    }

    /**
     * @method GET
     * @return array of estados
     */

    public function getEstados(){

        $estados = $this->soapWrapper->call('OU.ObtenerCatalogoEstados');
        $respuesta = $estados->ObtenerCatalogoEstadosResult;

        if( empty($respuesta) || empty($respuesta->EstadosDTO) ) return response()->json($this->mensaje, 400);
        return response()->json( $respuesta->EstadosDTO);

    }

    /**
     * @method POST
     * @param object {estado : idEstado}
     * @return array of municipios
     */
    public function getMunicipios( Request $request ){

        $params= json_decode($request->getContent(), true);
        $municipios = $this->soapWrapper->call('OU.ObtenerCatalogoMunicipios', [ $params ]);
        $respuesta = $municipios->ObtenerCatalogoMunicipiosResult;

        if( empty($respuesta) || empty($respuesta->MunicipiosDTO) ) return response()->json($this->mensaje, 400);
        return response()->json( $respuesta->MunicipiosDTO);

    }

    /**
     * @method POST
     * @param array
     * @return boolean if "OperacionExito"
     * @return string if MensajeError
     */

    public function addProyeccion( Request $request ){

        $params= json_decode($request->getContent(), true);
        $proyeccion = $this->soapWrapper->call('OU.AgregarProyeccionProfesional', [ $params ]);
        $respuesta = $proyeccion->AgregarProyeccionProfesionalResult;
        
        if( $respuesta->OperacionExito == FALSE ) return response()->json( ["error" => $respuesta->MensajeError], 400 );
        return response()->json( $respuesta );

    }

    /**
     * @method POST
     * @param array
     * @return boolean if "OperacionExito"
     * @return string if MensajeError
     */

    /**
     * dia unimex se deshabilita...
     */

    /*
    public function addDiaUnimex( Request $request ){

        $params= json_decode($request->getContent(), true);
        $diaU = $this->soapWrapper->call('OU.AgregarDiaUnimex', [ $params ]);
        $respuesta = $diaU->AgregarDiaUnimexResult;

        if( $respuesta->OperacionExito == FALSE ) return response()->json( ["error" => $respuesta->MensajeError], 400 );
        return response()->json( $respuesta );

    }
    */

    public function getEscuelaOrigen(){

        $escuela = $this->soapWrapper->call('OU.ObtenerCatalogoEscuelaOrigen');
        $respuesta =  $escuela->ObtenerCatalogoEscuelaOrigenResult;

        if( empty($respuesta) || empty($respuesta->Escuelas) ) return response()->json($this->mensaje, 400);
        return response()->json( $respuesta->Escuelas);
    }

    public function addEscuelaOrigen( Request $request ){

        $params = json_decode( $request->getContent(), true);
        $addEscuela = $this->soapWrapper->call('OU.ActualizarEscuelaOrigen', [$params]);
        $respuesta = $addEscuela->ActualizarEscuelaOrigenResult;

        if($respuesta == 0) return response()->json( ["estatus" => $respuesta], 400 );
        return response()->json( ["estatus" => $respuesta] );
    }

    public function getCarrerasDiaUnimex( Request $request){
        $params = json_decode( $request->getContent(), true);
        $carrerasDia = $this->soapWrapper->call('OU.ObtenerCatalogoCarrerasSinMeta', [$params]);
        $respuesta = $carrerasDia->ObtenerCatalogoCarrerasSinMetaResult;

        if( empty($respuesta) || empty($respuesta->CarerrasDTO) ) return response()->json($this->mensaje, 400);
        return response()->json( $respuesta->CarerrasDTO );

    }

    public function getHorariosDiaUnimex( Request $request ){
        $params = json_decode( $request->getContent(), true);
        $horariosDia = $this->soapWrapper->call('OU.ObtenerCatalogoTurnosSinMeta', [$params]);
        $respuesta = $horariosDia->ObtenerCatalogoTurnosSinMetaResult;

        if( empty($respuesta) || empty($respuesta->TurnosDTO) ) return response()->json($this->mensaje, 400);
        return response()->json( $respuesta->TurnosDTO );
    }

    /**
     * @method POST
     * @param array
     * @return boolean if "OperacionExito"
     * @return string if MensajeError
     */

    public function addProspectacion( Request $request ){

        $params= json_decode($request->getContent(), true);
        $prospectacion = $this->soapWrapper->call('OU.AgregarProspectacion', [ $params ]);
        $respuesta = $prospectacion->AgregarProspectacionResult;

        if( $respuesta->OperacionExito == FALSE ) return response()->json( ["error" => $respuesta->MensajeError], 400 );
        return response()->json( $respuesta );

    }

}
