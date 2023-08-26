<?php 

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CategoriaRoadMapCollection extends Model
{
    public static function codes()
    {
        return collect(
            [
                ['code' => 0,  'label' => 'DataBase'],
                ['code' => 1,  'label' => 'Backend'],
                ['code' => 2,  'label' => 'Frontend'],
                ['code' => 3,  'label' => 'Mobile'],
                ['code' => 4,  'label' => 'Testes'],
                ['code' => 5,  'label' => 'Infra'],
                ['code' => 6,  'label' => 'Integração'],
                ['code' => 7,  'label' => 'Segurança'],

            ]
        );
    }
}