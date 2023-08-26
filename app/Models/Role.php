<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Cliente;
use App\Models\Permission;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'cliente_id',
        'nome',
        'cor',
        'descricao'        
    ];

    public function cliente(){
        return $this->belongsTo(Cliente::class,'cliente_id','id');
    }

    public function permissions(){
        return $this->belongsToMany(Permission::class, 'roles_permissions', 'role_id', 'permission_id');
    }
}
