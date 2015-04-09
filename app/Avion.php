<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Avion extends Model {

	//Definimos la tabla Mysql que usará este modelo
	protected $table = "aviones";

	//Clave primaria de la tabla aviones
	//En este caso es el campo serie. Hay q indicarlo
	protected $primaryKey = "serie";

	//Campo de la tabla que se pueden asignar masivamente
	protected $fillable = array('modelo', 'longitud', 'capacidad', 'velocidad', 'alcance');

	//Ocultamos los campos timestamp en las consultas
	protected $hidden = ['created_at', 'updated_at'];

	//Relacion de aviones con fabricante 
	public function fabricante(){
		//La relacion sería 1 avion pertence a 1 fabricante
		return $this->belongsTo('App\Fabricante');
	}


}
