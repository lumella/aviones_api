<?php


public function store(Request $request){
	//metodo llamado al hacer post
	//comprobamos que recibimos todos los campos

	if(!$request->input('nombre') || !$request->input('direccion') || !$request->input('telefono')){
		//NO estamos recibiendo los campos necedsarios. Devolveremos error.
		return response()->json(['errors']=>array(['code'=>422, 'message'=>'Faltan datos necesarios para procesar el alta']));
	}

	//insertamos los datos recibidos en la tabla
	$nuevoFabricante = Fabricante::create($request->all());

	//devolvemos la respuesta http 201 (que quiere decir creado) + los datos del nuevo fabricante + una cabecera de location
	$respuesta = Response::make(json_encode(['data'=>$nuevoFabricante]),201)->header('Location', 'http://www.dominio.local/fabricantes/'.$nuevoFabricante->id)->header('Content-Type', 'application/json');

	return $respuesta;
}

public function destroy($id)
	{
		Fabricante::destroy($id);

	}