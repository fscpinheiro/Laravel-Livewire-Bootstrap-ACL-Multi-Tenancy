<?php 

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SituacaoCollection extends Model
{
    public static function codes()
    {
        return collect(
            [
                ['code' => 0,  'label' => 'cancelado'],
                ['code' => 1,  'label' => 'ativo'],
                ['code' => 2,  'label' => 'suspenso'],
            ]
        );
    }
}