<?php 

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SituacaoRoadMapCollection extends Model
{
    public static function codes()
    {
        return collect(
            [
                ['code' => 0,  'label' => 'Desenvolvimento'],
                ['code' => 1,  'label' => 'Planejamento'],
                ['code' => 2,  'label' => 'Concluido'],
                ['code' => 3,  'label' => 'Pausado'],
                ['code' => 4,  'label' => 'Cancelado'],
                ['code' => 5,  'label' => 'Teste'],
                ['code' => 6,  'label' => 'Revisão'],

            ]
        );
    }
}