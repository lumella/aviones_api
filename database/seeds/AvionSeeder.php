<?php

use Illuminate\Database\Seeder;

//Hace uso del modelo de avion
use App\Avion;

//Hace uso del modelo fabricante para saber cuantos fabricantes hay actualmente
use App\Fabricante;

//usamos el faker que instalamos antes
use Faker\Factory as Faker;

class AvionSeeder extends Seeder {

	public function run()
	{
		//Creamos una instancia de Faker
		$faker = Faker::create();

		//Necesitamos saber cuantos fabricantes tenemos
		$cuantos=Fabricante::all()->count();

		//Vamos a cubrir 20 aviones
		for($i = 0; $i < 20; $i++){
			//Cuando llamamos al metodo create del modelo avion se está creando una nueva fila en la tabla de fabricantes
			//Ver info de Active Record - Eloquent ORM
			Avion::create(
				['modelo'=>$faker->word(),
				'longitud' => $faker->randomFloat(),
				'capacidad' => $faker->randomNumber(),
				'velocidad' => $faker->randomNumber(),
				'alcance' => $faker->randomNumber(),
				'fabricante_id' => $faker->numberBetween(1, $cuantos)]
				);
		}
	}

}
