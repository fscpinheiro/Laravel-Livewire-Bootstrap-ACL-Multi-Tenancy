<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Cliente;
use App\Models\App;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'cliente_id',
        'app_id',
        'nome',
        'icone',
        'rota',
        'parentId',
        'posicao'
    ];

    public function cliente(){
        return $this->belongsTo(Cliente::class,'cliente_id','id');
    }

    public function app()
    {
        return $this->belongsTo(App::class)->withDefault();
    }
    
    public function submenus(){
        return $this->hasMany(Menu::class, 'parentId', 'id');
    }
}
