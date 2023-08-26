<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Historico;
use App\Models\Menu;

class Cliente extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'clientes';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'razaosocial',
        'fantasia',
        'slugname',
        'documento',
        'status',
        'logo'
    ];

    public function users(){
        return $this->hasMany(User::class, 'cliente_id', 'id');
    }

    public function roles(){
        return $this->hasMany(Role::class, 'cliente_id', 'id');
    }

    public function historicos(){
        return $this->hasMany(Historico::class, 'cliente_id', 'id');
    }

    public function menu(){
        return $this->hasMany(Menu::class, 'cliente_id','id');
    }

    public function apps(){
        return $this->belongsToMany(App::class, 'clientes_apps', 'cliente_id', 'app_id');
    }
}
