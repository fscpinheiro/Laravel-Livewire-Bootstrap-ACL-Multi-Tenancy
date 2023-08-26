<?php 

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserSituacaoCollection extends Model
{
    public static function codes()
    {
        return collect(
            [
                ['code' => 0,  'label' => 'cancelado', 'cor' => '#6c757d'],
                ['code' => 1,  'label' => 'ativo', 'cor' => '#198754'],
                ['code' => 2,  'label' => 'bloqueado', 'cor' => '#ffc107'],
            ]
        );
    }
}