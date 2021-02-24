<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\DeliveryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/login',[LoginController::class,'index'])->name('login');
Route::post('/login',[LoginController::class,'validateLogin']);
Route::get('/logout',[LoginController::class,'logout'])->name('logout');
Route::get('/error',function (){
    return view('error');
})->name('error');

Route::group(['middleware' => 'auth'], function() {
    Route::get('/',[HomeController::class,'index'])->name('home');
    Route::post('/search',[HomeController::class,'search'])->name('search');

    Route::group(['middleware' => 'admin'], function() {
        //Purchase Order
        Route::get('/po',[PurchaseOrderController::class,'index'])->name('po');
        Route::get('/po/create',[PurchaseOrderController::class,'create'])->name('create.po');
        Route::post('/po/update',[PurchaseOrderController::class,'update'])->name('update.po');
        Route::post('/po/delete',[PurchaseOrderController::class,'delete'])->name('delete.po');
        Route::get('/po/report',[PurchaseOrderController::class,'printReport']);
        Route::post('/po/report',[PurchaseOrderController::class,'storeDate']);
        //Items in Purchase Order
        Route::get('/po/items/{po_id}',[PurchaseOrderController::class,'items'])->name('items.po');
        Route::post('/po/items/update',[PurchaseOrderController::class,'updatePurchaseItem'])->name('update.purchaseItem');
        Route::get('/po/items/amount/{po_id}',[PurchaseOrderController::class,'calculateAmount'])->name('total.purchaseItem');
        Route::get('/po/{id}',[PurchaseOrderController::class,'edit'])->name('edit.po');

        //Manage Deliveries
        Route::get('/delivery',[DeliveryController::class,'index'])->name('delivery');
        Route::post('/delivery/search',[DeliveryController::class,'search'])->name('search.po');
        Route::post('/delivery/submit',[DeliveryController::class,'store'])->name('submit.delivery');
        Route::get('/delivery/item/{id}',[DeliveryController::class,'showItemDescription']);

        Route::get('/delivery/{id}',[DeliveryController::class,'show'])->name('show.po');

        //Manage Items
        Route::post('/items/update',[ItemController::class,'update'])->name('update.item');
        Route::get('/items/create/default/{po_id}',[ItemController::class,'createDefaultValue'])->name('default.item');
        Route::post('/items/remove/',[ItemController::class,'delete'])->name('delete.item');


        //Mange Suppliers
        Route::get('/misc/supplier',[SupplierController::class,'index'])->name('supplier');
        Route::post('/misc/supplier',[SupplierController::class,'store'])->name('add.supplier');
        Route::get('/misc/supplier/{id}',[SupplierController::class,'edit'])->name('edit.supplier');
        Route::put('/misc/supplier/{id}',[SupplierController::class,'update'])->name('update.supplier');
        Route::delete('/misc/supplier/{id}',[SupplierController::class,'delete'])->name('delete.supplier');

        //Manage Units
        Route::resource('/misc/unit',UnitController::class);
    });
});


Route::get('/load',function (){
    return view('load.load');
});
