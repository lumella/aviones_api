<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Fabricante extends Model {

	//Definimos la tabla Mysql que usará este modelo
	protected $table="fabricantes";

	//Atributos de la tabla que se pueden rellenar de forma masiva
	protected $fillable= array('nombre', 'direccion', 'telefono');

	//Ocultamos los campos timestamp en las consultas
	protected $hidden=['created_at', 'updated_at'];

	//Relacion de fabricante con aviones
	public function aviones(){
		//La relacion sería 1 fabricante hace muchos aviones
		return $this->hasMany('App\Avion');
	}
}
