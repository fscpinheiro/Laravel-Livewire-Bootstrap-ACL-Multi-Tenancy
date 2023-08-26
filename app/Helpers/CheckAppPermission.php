<?php 
use App\Models\Permission;
use App\Models\Cliente;
use App\Models\User;
use App\Models\Role;
use App\Models\App;
use Illuminate\Support\Facades\Log;

if (!function_exists('check_permission')) {
    function check_permission($app_id, $permission_name)
    {
        $user = Auth::user();
        DB::enableQueryLog();
        $permission = Permission::where('app_id', $app_id)
            ->where('nome', $permission_name)
            ->first();
        $query = DB::getQueryLog()[0];
        //Log::info($query['query']);
        //Log::info($query['bindings']);
        if (!$permission) {
            return false;
        }        

        $userPermission = $user->permissions->first(function ($permission) use ($app_id, $permission_name) {
            return $permission->app_id == $app_id && $permission->nome == $permission_name;
        });
        if ($userPermission) {
            if ($userPermission->pivot->permitido) {
                return true;                
            } else {
                return false;
            }    
        } elseif ($user->role->permissions->contains(function ($permission) use ($app_id, $permission_name) {
            return $permission->app_id == $app_id && $permission->nome == $permission_name;
        })) {
            return true;            
        }
        return false;
    }
}