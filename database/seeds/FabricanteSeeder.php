<?php

use Illuminate\Database\Seeder;

//Hace uso del modelo de fabricante
use App\Fabricante;

//usamos el faker que instalamos antes
use Faker\Factory as Faker;

class FabricanteSeeder extends Seeder {


	public function run()
	{
		//Creamos una instancia de Faker
		$faker = Faker::create();

		//Vamos a cubrir 5 fabricantes
		for($i = 0; $i < 5; $i++){
			//Cuando llamamos al metodo create del modelo fabricante se está creando una nueva fila en la tabla de fabricantes
			//Ver info de Active Record - Eloquent ORM
			Fabricante::create(
				['nombre'=>$faker->word(),
				'direccion' => $faker->word(),
				'telefono' => $faker->randomNumber()]
				);
		}
	}

}