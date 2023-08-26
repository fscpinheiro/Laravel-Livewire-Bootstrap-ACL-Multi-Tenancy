<?php 

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SituacaoClienteCollection extends Model
{
    public static function codes()
    {
        return collect(
            [
                ['code' => 0,  'label' => 'cancelado', 'cor' => 'bg-secondary'],
                ['code' => 1,  'label' => 'ativo', 'cor' => 'bg-success'],
                ['code' => 2,  'label' => 'suspenso', 'cor' => 'bg-warning'],
            ]
        );
    }
}