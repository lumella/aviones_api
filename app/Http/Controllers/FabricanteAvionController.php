<?php namespace App\Http\Controllers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Fabricante;
use App\Avion;
use Response;

//Activamos el uso de las funciones de cache
use Illuminate\Support\Facades\Cache;

class FabricanteAvionController extends Controller {

	public function __construct()
	{
		$this->middleware('auth.basic',['only'=>['store', 'update', 'destroy']]);
	}
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($idFabricante)
	{
		// Mostramos todos los aviones de un fabricante.
		// Comprobamos si el fabricante existe
		$fabricante=Fabricante::find($idFabricante);
		if (! $fabricante)
		{
			// En code podríamos indicar un código de error personalizado de nuestra aplicación si lo deseamos.
			return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra un fabricante con ese código.'])],404);
		}

		$fabricanteavion = Cache::remember('cachefabricanteaviones', 1, function() use($fabricante){
			return $fabricante->aviones()->get();
		});

		return response()->json(['status'=>'ok','data'=>$fabricanteavion],200);
		//Sin cache
		//return response()->json(['status'=>'ok','data'=>$fabricante->aviones()->get()],200);

		// return response()->json(['status'=>'ok','data'=>$fabricante->aviones],200);
	}
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($idFabricante,Request $request)
	{
		// Damos de alta un avión de un fabricante.
		// Comprobamos que recibimos todos los datos de avión.
		if (! $request->input('modelo') || ! $request->input('longitud') ||! $request->input('capacidad') ||! $request->input('velocidad') ||! $request->input('alcance') )
		{
			// Error 422 Unprocessable Entity.
			return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan datos necesarios para el alta de avión.'])],422);
		}
		// Compruebo si existe el fabricante.
		$fabricante=Fabricante::find($idFabricante);
		if (! $fabricante)
		{
			return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra un fabricante con ese código.'])],404);
		}
		// Damos de alta el avión de ese fabricante.
		$nuevoAvion=$fabricante->aviones()->create($request->all());
		// Devolvemos un JSON con los datos, código 201 Created y Location del nuevo recurso creado.
		$respuesta= Response::make(json_encode(['data'=>$nuevoAvion]),201)->header('Location','http://www.dominio.local/aviones/'.$nuevoAvion->serie)->header('Content-Type','application/json');
		return $respuesta;
	}
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($idFabricante, $idAvion, Request $request)
	{
		//Recibimos peticion put o patch 
		//Comprobamos si el fabricante existe
		$fabricante = Fabricante::find($idFabricante);

		if(! $fabricante){
			return response()->json(['errors'=>array(['code'=>404, 'message'=>'No se encuentra un fabricante con ese codigo'])], 404)
		}
		//Comprobamos que el avion pertenece al fabricante
		$avion = $fabricante->aviones()->find($idAvion);

		if(! $avion){
			return response()->json(['errors'=>array(['code'=>404, 'message'=>'No se encuentra un avion con ese codigo asociado al fabricante'])],404)
		}

		//Listado de campos recibidos del formulario de actualizacion
		$modelo = $request->input('modelo');
		$longitud = $request->input('longitud');
		$capacidad = $request->input('capacidad');
		$velocidad = $request->input('velocidad');
		$alcance = $request->input('alcance');

		//Comprobamos si el método es PATCH(actualizacion parcial) o PUT(actualizacion total)
	if($request->method() === 'PATCH') //Actualizacion parcial
		{
			$bandera = false;
			//Comprobamos campo a campo si hemos recibido datos
			if($modelo){
				//Actualizamos este campo en la tabla
				$avion->modelo = $modelo;
				$bandera = true;
			}

			if($longitud){
				//Actualizamos este campo en la tabla
				$avion->longitud = $longitud;
				$bandera = true;
			}

			if($capacidad){
				//Actualizamos este campo en la tabla
				$avion->capacidad = $capacidad;
				$bandera = true;
			}

			if($velocidad){
				//Actualizamos este campo en la tabla
				$avion->velocidad = $velocidad;
				$bandera = true;
			}

			if($alcance){
				//Actualizamos este campo en la tabla
				$avion->alcance = $alcance;
				$bandera = true;
			}
			//Comprobamos la bandera
			if($bandera){
				//Almacenamos los cambios del modelo en la tabla
				$avion->save();
				return response()->json(['status'=>'ok', 'data'=>$avion],200);
			}
			else{
				//Codigo 304 q quiere decir Not modified
				return response()->json(['errors'=>array(['code'=>304, 'message'=>'no se ha modificado ningún dato del avion'])],304);
			}
		}

		if($request->method() === 'PUT') //Actualizacion total
		{
			//Chequeamos que recibimos todos los campos
			if(!$modelo || !$longitud || !$capacidad || !$velocidad || !$alcance){	
				//Codigo 422 unprocessable Entity
				return response()->json(['errors'=>array(['code'=>422, 'message' => 'Faltan valores para completar el procesamiento'])],422)
			}

			//Actualizamos el modelo avion
			$avion->modelo = $modelo;
			$avion->longitud = $longitud;
			$avion->capacidad = $capacidad;
			$avion->velocidad = $velocidad;
			$avion->alcance = $alcance;

			//Grabamos los datos del modelo en la tabla
			$avion->save();

			return response()->json(['status'=>'ok', 'data'=>$avion],200);
		}

	}
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($idFabricante,$idAvion)
	{
		// Compruebo si existe el fabricante.
		$fabricante=Fabricante::find($idFabricante);
		if (! $fabricante)
		{
			return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra un fabricante con ese código.'])],404);
		}
		// Compruebo si existe el avion.
		$avion=$fabricante->aviones()->find($idAvion);
		if (! $avion)
		{
			return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra un avión asociado a ese fabricante.'])],404);
		}
		// Borramos el avión.
		$avion->delete();
		// Devolvemos código 204 No Content.
		return response()->json(['code'=>204,'message'=>'Se ha eliminado el avión correctamente.'],204);
	}
}