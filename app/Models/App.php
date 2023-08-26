<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Permission;
use App\Models\Menu;
use App\Models\Historico;

class App extends Model
{
    use HasFactory;

    protected $table = 'apps';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nome',
        'icone',
        'descricao',
        'modelo'
    ];

   public function permissions(){
    return $this->hasMany(Permission::class, 'app_id', 'id');
   }
    

    public function clientes() {
        return $this->belongsToMany(Cliente::class, 'clientes_apps', 'app_id', 'cliente_id');
    }
    
    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function historicos(){
        return $this->hasMany(Historico::class, 'app_id', 'id');
    }
}
