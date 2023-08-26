<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\App;
use App\Models\User;
use App\Models\Role;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'permissions';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'app_id',
        'nome',
        'descricao'        
    ];

    public function app(){
        return $this->belongsTo(App::class);
    }
    
    public function users(){
        return $this->belongsToMany(User::class, 'users_permissions', 'permission_id', 'user_id');
    }

    public function roles(){
        return $this->belongsToMany(Role::class, 'roles_permissions', 'permission_id', 'role_id');
    }
}
