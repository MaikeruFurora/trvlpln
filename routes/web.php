<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ActivityListController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupervController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| 
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['guest:web', 'preventBackHistory'])->name('auth.')->group(function () {
    Artisan::call('view:clear');
    Route::get('/', function () {
        return view('auth/signin');
    })->name('signin');
    Route::post('/save', [AuthController::class, 'loginPost'])->name('login.post');
});



Route::get('/vcard',function(){
    return view('vcard');
});


Route::middleware(['auth:web','preventBackHistory','auth.user'])->name('authenticate.')->prefix('auth/')->group(function(){

    // BDO API
    Route::get('activity',[ActivityController::class,'index'])->name('activity');
    Route::post('activity/store',[ActivityController::class,'store'])->name('activity.store');
    Route::post('activity/list/{user}',[ActivityController::class,'list'])->name('activity.list');
    Route::post('activity/update/{activity}',[ActivityController::class,'update'])->name('activity.update');
    Route::post('activity/update/info/{activity}',[ActivityController::class,'updateInfo'])->name('activity.update.info');
    Route::post('activity/update/date/{activity}',[ActivityController::class,'updateDate'])->name('activity.update.date');
    Route::get('activity/info/{activity}',[ActivityController::class,'info'])->name('activity.info');
    Route::delete('activity/destroy/{activity}',[ActivityController::class,'destroy'])->name('activity.destroy');

    // ADMIN API
    Route::get('dashboard',[AdminController::class,'dashboard'])->name('dashboard');
    Route::get('vw',[AdminController::class,'index'])->name('admin');
    Route::get('vw/report',[AdminController::class,'report'])->name('admin.report');
    Route::get('user',[UserController::class,'index'])->name('user');
    Route::get('user/list',[UserController::class,'list'])->name('user.list');
    Route::get('user/api/list',[UserController::class,'Apilist'])->name('user.api.list');
    Route::post('user/store',[UserController::class,'store'])->name('user.store');

    Route::get('actvty-list',[ActivityListController::class,'index'])->name('actvtylist');
    Route::post('actvty-list/store',[ActivityListController::class,'store'])->name('actvtylist.store');
    Route::get('actvty-list/list',[ActivityListController::class,'list'])->name('actvtylist.list');
    
    Route::get('product',[ProductController::class,'index'])->name('product');
    Route::post('product/store',[ProductController::class,'store'])->name('product.store');
    Route::get('product/list',[ProductController::class,'list'])->name('product.list');
    Route::get('product/search', [ProductController::class, 'search']);

    Route::get('group',[GroupController::class,'index'])->name('group');
    Route::post('group/store',[GroupController::class,'store'])->name('group.store');
    Route::get('group/list',[GroupController::class,'list'])->name('group.list');

    Route::get('warehouse',[WarehouseController::class,'index'])->name('warehouse');
    Route::post('warehouse/store',[WarehouseController::class,'store'])->name('warehouse.store');
    Route::get('warehouse/list',[WarehouseController::class,'list'])->name('warehouse.list');


    Route::get('audit',[AdminController::class,'audit'])->name('audit');
    Route::get('audit/list',[AdminController::class,'auditList'])->name('audit.list');


    Route::get('sprvsr',[SupervController::class,'index'])->name('supervisor');
    Route::get('sprvsr/report',[SupervController::class,'report'])->name('supervisor.report');

     //signout
     Route::post('signout', [UserController::class, 'signout'])->name('signout');

});

