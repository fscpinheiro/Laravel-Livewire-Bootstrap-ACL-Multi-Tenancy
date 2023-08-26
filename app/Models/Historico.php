<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Cliente;
use App\Models\App;

class Historico extends Model
{
    use HasFactory;

    protected $table = 'historicos';
    protected $primaryKey = 'id';

    protected $fillable = [
        'cliente_id',
        'user_id',
        'app_id',
        'datahora',
        'ip',        
        'acao',
        'browser',
        'so'
    ];

    public function cliente(){
        return $this->belongsTo(Cliente::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function app(){
        return $this->belongsTo(App::class);
    }
}
