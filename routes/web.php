<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register' => false]);

// Route::get('/home', 'HomeController@index')->name('home');




    Route::group(['prefix' => LaravelLocalization::setLocale(),
    'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]], function()
    {

            Route::group(['prefix' => 'admin','namespace' => 'admin','as'=>'admin.','middleware'=>'auth'],function(){

                // Tickets
                Route::get('/','TicketsController@index');
                Route::get('tickets/{id}/changestatus','TicketsController@changeStatus')->name('tickets.changestatus')->middleware(['permission:Open Ticket|Close Ticket']);
                Route::resource('tickets','TicketsController',[
                    'names' => [
                        'index' => 'tickets.index','create' => 'tickets.create',
                        'store' => 'tickets.store','edit'  => 'tickets.edit',
                        'update'  => 'tickets.update','destroy'  => 'tickets.destroy'
                    ]
                ])->except(['show']);

                // Users
                Route::resource('users','UsersController',[
                    'names' => [
                        'index' => 'users.index','create' => 'users.create',
                        'store' => 'users.store','edit'  => 'users.edit','show'  => 'users.show',
                        'update'  => 'users.update','destroy'  => 'users.destroy'
                    ],

                ])->middleware('can:Manage Users');

                // Roles
                Route::post('permission','RolesController@createpermission')->name('permission.create')->middleware('role:Super Admin');
                Route::resource('roles','RolesController',[
                    'names' => [
                        'index' => 'roles.index','create' => 'roles.create',
                        'store' => 'roles.store','edit'  => 'roles.edit','show'  => 'roles.show',
                        'update'  => 'roles.update','destroy'  => 'roles.destroy'
                    ]
                ])->middleware('role:Super Admin');

            });

});
