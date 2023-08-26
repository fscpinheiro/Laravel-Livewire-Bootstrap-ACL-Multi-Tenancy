<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Models\Cliente;
use App\Models\Role;
use App\Models\ActionHistory;
use App\Models\Permission;
use App\Models\Historico;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'cliente_id',
        'role_id',
        'name',
        'email',
        'password',
        'perfil',
        'situacao'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function cliente(){
        return $this->belongsTo(Cliente::class);
    }

    public function role(){
        return $this->belongsTo(Role::class);
    }

    public function historico(){
        return $this->hasMany(Historico::class, 'user_id', 'id');
    }

    public function permissions(){
        return $this->belongsToMany(Permission::class, 'users_permissions', 'user_id', 'permission_id')->withPivot('permitido');
    }
}
