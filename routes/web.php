<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\NavegateController;
use App\Models\Menu;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;


Route::group(['middleware' => ['auth', 'ShareMenuData']], function () {
    if (Schema::hasTable('menus')) {
        $menus = Menu::all();
        foreach ($menus as $menu) {
            if (!in_array($menu->rota, ['home', '/', 'login', 'meuperfil'])) {
                Route::get($menu->rota, [NavegateController::class, 'handleRequest'])->middleware('CUP:' . $menu->app_id)->name($menu->nome);
            }
        }
    }else{
        Log::info("sem menus");
    }
});


Route::get('/', [NavegateController::class,'inicio'])->name('welcome');
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware(['auth', 'ShareMenuData']);
Route::get('/meuperfil', [App\Http\Controllers\HomeController::class, 'perfil'])->name('meuperfil')->middleware(['auth', 'ShareMenuData']);
Auth::routes();

Route::view('/powergrid', 'powergrid-demo');


