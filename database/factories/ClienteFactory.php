<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Cliente;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cliente>
 */
class ClienteFactory extends Factory
{
    protected $model = Cliente::class;
    
    public function definition(): array
    {
        $uid = Str::uuid()->toString();
        return [
            'id' => $this->faker->uuid,
            'razaosocial' => $this->faker->company,
            'fantasia' => $this->faker->companySuffix,
            'slugname' => $this->faker->slug,
            'documento' => $this->faker->randomNumber(3) .".".$this->faker->randomNumber(3) .".".$this->faker->randomNumber(3) ."/0001-" . $this->faker->randomNumber(2),
            'situacao' => $this->faker->numberBetween(0, 2),//$this->faker->boolean,
            'logo' => null,
            'tpcliente' => 'PJ',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
