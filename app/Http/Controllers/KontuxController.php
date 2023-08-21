<?php

namespace App\Http\Controllers;
use Artisaninweb\SoapWrapper\SoapWrapper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KontuxController extends Controller
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

    /**
     * 
     * Obtiene los planteles del servicio kontux
     * @return array de los planteles de unimex
     * 
     */
    
     public function getPlanteles(){
    
        $planteles = $this->soapWrapper->call('KNTX.TraePlantel');
        $respuesta = $planteles->TraePlantelResult;

        if( empty($respuesta) || empty($respuesta->PlantelesDTO) ) return response()->json($this->mensaje, 400);
        return response()->json( $respuesta->PlantelesDTO );
    }

    /**
     * Obtiene los niveles del servicio Kontux
     * @param object|array Request $request
     * @return array 
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
     * Obtiene los periodos segun la meta de MKT
     * @param @param object|array Request $request
     * @return array|object Puede ser un objeto siempre y cuando solo haya un periodo en la oferta
     */

    public function getPeriodos( Request $request){
        
        $params = json_decode($request->getContent(), true);
        $periodos = $this->soapWrapper->call('KNTX.ObtenerCatalogoPeriodoEscolar', [ $params ]);
        $respuesta = $periodos->ObtenerCatalogoPeriodoEscolarResult;

        if( empty($respuesta) || empty($respuesta->PeriodosDTO) ) return response()->json($this->mensaje, 400);
        return response()->json( $respuesta->PeriodosDTO );

    }

    /**
     * Obtiene las Carreras de Kontux Service
     * @param object|array Request $request
     * @return array|object Puede ser un objeto cuando solo haya una carrera ofertada
     */
    public function getCarreras( Request $request){

        $params= json_decode($request->getContent(), true);
        $carreras = $this->soapWrapper->call('KNTX.TraeCarrera', [ $params ]);
        $respuesta = $carreras->TraeCarreraResult;

        if( empty($respuesta) || empty($respuesta->CarerrasDTO) ) return response()->json($this->mensaje, 400);
        return response()->json( $respuesta->CarerrasDTO);

    }

    /**
     * Obtiene los Turnos(Horarios) desde Kontux Service
     * @param object|array Request $request
     * @return array|object Puede ser un objeto si solo hay un turno disponible segun la carrera
     */

    public function getTurnos( Request $request){

        $params= json_decode($request->getContent(), true);
        $turnos = $this->soapWrapper->call('KNTX.TraeTurno', [ $params ]);
        $respuesta = $turnos->TraeTurnoResult;

        if( empty($respuesta) || empty($respuesta->TurnosDTO) ) return response()->json($this->mensaje, 400);
        return response()->json( $respuesta->TurnosDTO);

    }

    /**
     * Valida si ya existe el propspecto mediante el correo electronico
     * @param mixed correo electronico
     * @return array Datos del prospecto
     */

    public function validaCelular( Request $request ){
        $params = json_decode( $request->getContent(), true);
        $validacion = $this->soapWrapper->call('KNTX.ValidaCelular', [$params]);
        $respuesta = $validacion->ValidaCelularResult;

        if( empty($respuesta) || empty($respuesta->ProspectoKontux) ) return response()->json($this->mensaje, 400);
        return response()->json( $respuesta->ProspectoKontux);
    }

    /**
     * Function de pruba para obtener los archivos del Storage.
     * @param object|array Request $request
     * @return mixed contiene la rua del archivo
     */

    public function getFiles( Request $request){

        $planteles = [
            2 => 'izcalli',
            3 => 'satelite',
            4 => 'polanco',
            5 => 'veracruz'
        ];
        $params = json_decode( $request->getContent(), true);

        if( $params['tipo'] == 'archivo'){
            $file = Storage::disk('public')
                    ->get( $planteles[$params['clavePlantel']].'/'.$params['ubicacion'].'/'.$params['claveCarrera'].'.pdf');
            return( response($file, 200))->header('Content-Type', 'application/pdf');
        }else if( $params['tipo'] == 'video' ){
            $file = Storage::disk('public')
                    ->get( $planteles[$params['clavePlantel']].'/'.$params['ubicacion'].'/'.$params['claveCarrera'].'.mp4');
            return( response($file, 200))->header('Content-Type', 'video/mp4');
        }else{
            return response(['error' => 'archivo no encontrado'], 400);
        }
    }

    /**
     * Obtienen la ubicacion y demas datos del plantel seleccionado en la aplicación
     * @param array|object con la clave del plantel
     * @return array datos del plantel seleccionado
     */

    public function getUbicacionPlantel( Request $request ){
        $params = $request->all();
        $ubicacionPlantel = $this->soapWrapper->call('KNTX.ObtenerUbicacionPlantel', [$params]);
        $respuesta = $ubicacionPlantel->ObtenerUbicacionPlantelResult;

        if( empty($respuesta) || empty($respuesta->PlantelKontux)) return response()->json( $this->mensaje, 400);
        return response()->json( $respuesta->PlantelKontux);
    }

    /**
     * Obtiene las rutas del documento a mostrar en la aplicación
     * @param array Debe contener plantel, nivel, carrera, turno y tipo de documento
     * @return array Contiene la ruta del archivo
     */
    public function getDocumentos( Request $request){
        $params = $request->all();
        $documentos =  $this->soapWrapper->call('KNTX.TraeDocumentosKontux', [$params]);
        $respuesta = $documentos->TraeDocumentosKontuxResult;

        if( empty($respuesta) || empty($respuesta->DocumentosKontux)) return response()->json( $this->mensaje, 400);
        return response()->json( $respuesta->DocumentosKontux);
    }

    /**
     * Obtiene la  ruta del documento segun el nivel seleccionado
     * @param array Contiene plantel, nivel y tipo de documento
     * @return array Contiene la ruta del documento segun el nivel seleccionado
     */

    public function getDocumentosNivel( Request $request){
        $params = $request->all();
        $documentosNivel = $this->soapWrapper->call('KNTX.TraeDocumentosKontuxNivel', [$params]);
        $respuesta = $documentosNivel->TraeDocumentosKontuxNivelResult;

        if( empty($respuesta) || empty($respuesta->DocumentosKontux)) return response()->json( $this->mensaje, 400);
        return response()->json( $respuesta->DocumentosKontux);
    }

    /**
     * Actualiza el plantel del prospecto
     * @param array Contiene folioCRM y clavePlantel
     * @return boolean
     */

    public function updatePlantel( Request $request){
        $params = $request->all();
        $actualizaPlantel = $this->soapWrapper->call('KNTX.ActualizaPlantel', [$params]);
        $respuesta = $actualizaPlantel->ActualizaPlantelResult;

        if( empty($respuesta) || !$respuesta ) return response()->json( $this->mensaje, 400);
        return response()->json(['respuesta' => $respuesta]);
    }

    /**
     * ACtualiza el nivel del prospecto
     * @param array Contiene folioCRM y clavePlantel
     * @return boolean
     */

    public function updateNivel( Request $request){
        $params = $request->all();
        $actualizaNivel = $this->soapWrapper->call('KNTX.ActualizaNivel', [$params]);
        $respuesta = $actualizaNivel->ActualizaNivelResult;

        if( empty($respuesta) || !$respuesta ) return response()->json( $this->mensaje, 400);
        return response()->json(['respuesta' => $respuesta]);
    }

    /**
     * Actualiza la carrera del prospecto
     * @param array Contiene folioCRM y clavePlantel
     * @return boolean
     */

    public function updateCarrera( Request $request){
        $params = $request->all();
        $actualizaCarrera = $this->soapWrapper->call('KNTX.ActualizaCarrera', [$params]);
        $respuesta = $actualizaCarrera->ActualizaCarreraResult;

        if( empty($respuesta) || !$respuesta ) return response()->json( $this->mensaje, 400);
        return response()->json(['respuesta' => $respuesta]);
    }

    /**
     * Actualiza el turno del prospecto
     * @param array Contiene folioCRM y clavePlantel, claveTurno
     * @return boolean
     */

    public function updateTurno( Request $request){
        $params = $request->all();
        $actualizaTurno = $this->soapWrapper->call('KNTX.ActualizaTurno', [$params]);
        $respuesta = $actualizaTurno->ActualizaTurnoResult;

        if( empty($respuesta) || !$respuesta ) return response()->json( $this->mensaje, 400);
        return response()->json(['respuesta' => $respuesta]);
    }

}
