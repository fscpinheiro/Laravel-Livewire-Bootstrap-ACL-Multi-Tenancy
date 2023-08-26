<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RoadMap;
use Illuminate\Support\Str;

use App\Models\CategoriaRoadMapCollection;
use App\Models\SituacaoRoadMapCollection;

class RoadMapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        //$categorias = CategoriaRoadMapCollection::codes()->pluck('label')->toArray();
        $categorias = CategoriaRoadMapCollection::codes()->pluck('code')->toArray();
        //$situacoes = SituacaoRoadMapCollection::codes()->pluck('label')->toArray();
        $situacoes = SituacaoRoadMapCollection::codes()->pluck('code')->toArray();

        for ($i = 0; $i < 8; $i++) {
            $estimatedCompletionDate = $faker->dateTimeBetween('now', '+1 year');
            $completedDate = $faker->boolean(50) ? $faker->dateTimeBetween('now', $estimatedCompletionDate) : null;

            RoadMap::create([
                'id' => Str::uuid()->toString(),
                'feature' => $faker->sentence,
                'category' => $faker->randomElement($categorias),
                'status' => $faker->randomElement($situacoes),
                'version' => '1.0',
                'estimated_completion_date' => $estimatedCompletionDate,
                'completed_date' => $completedDate,
                'notes' => $faker->paragraph,
            ]);
        }
    }
}
