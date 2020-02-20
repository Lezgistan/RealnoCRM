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



Route::get('/','HomeController@index')->name('home');


//Auth::routes();
Auth::routes(['register' => false, 'reset' => true, 'verify' => true]);


Route::group(['middleware' => ['auth']], function () {
    Route::resource('users','UserController');
    Route::resource('roles','RoleController');
    Route::resource('permissions','PermissionController');
    Route::post('documents/{document}/versions/store','DocumentController@versionsStore')->name('document.versions.store');

    Route::resource('documents','DocumentController');
    Route::get('users/{user}/roles', 'UserController@roles')->name('users.roles');
    Route::get('users/{user}', 'UserController@show')->name('users.show');
    Route::patch('users/{user}/roles', 'UserController@rolesUpdate')->name('users.roles.update');
    Route::get('users/{user}/password', 'UserController@password')->name('users.password');
    Route::patch('users/{user}/password', 'UserController@passwordUpdate')->name('users.password.update');
    Route::get('users/{user}/logs','UserController@logs')->name('users.logs');
    Route::get('users/{user}/documents','UserController@documents')->name('users.docs');
//    Route::get('documents/{user_doc}/versions','DocumentController@versions')->name('documents.versions');
});





