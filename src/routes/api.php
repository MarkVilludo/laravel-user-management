<?php

/*
|--------------------------------------------------------------------------
|  Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/login', 'MarkVilludo\Permission\Controllers\Api\PassportController@login')->name('login');
Route::post('/register', 'MarkVilludo\Permission\Controllers\Api\PassportController@register')->name('register');

//group by v1
Route::prefix('v1')->group(function () {

	//EDIT role
	Route::prefix('permissions')->group(function () {
		//All permissions
		Route::get('/', 'MarkVilludo\Permission\Controllers\Api\PermissionController@index')->name('api.permissions');

		Route::get('/create', 'MarkVilludo\Permission\Controllers\Api\PermissionController@create')->name('api.group_permissions');

		//Create new permission
		Route::post('/', 'MarkVilludo\Permission\Controllers\Api\PermissionController@store');
		//Update permission
		Route::post('/{id}', 'MarkVilludo\Permission\Controllers\Api\PermissionController@update');
		//Delete permission
		Route::delete('/{id}', 'MarkVilludo\Permission\Controllers\Api\PermissionController@destroy');
	});

	//get permissions in edit role
	Route::get('/role-permissions', 'MarkVilludo\Permission\Controllers\Api\RoleController@rolePermissions')->name('api.role_permissions');

	
	Route::prefix('roles')->group(function () {
		//All roles
		Route::get('{id}/edit', 'MarkVilludo\Permission\Controllers\Api\RoleController@edit')->name('api.edit_group_permissions');
		
		Route::get('/', 'MarkVilludo\Permission\Controllers\Api\RoleController@index')->name('api.roles');
		
		Route::post('/', 'MarkVilludo\Permission\Controllers\Api\RoleController@store');
		//Update role
		Route::post('/{id}', 'MarkVilludo\Permission\Controllers\Api\RoleController@update');
		//Delete role
		Route::delete('/{id}', 'MarkVilludo\Permission\Controllers\Api\RoleController@destroy');
	});
	 	
	//Users
	Route::prefix('users')->group(function () {
		//user list
	  	Route::get('/','MarkVilludo\Permission\Controllers\Api\UserController@index')->name('api.users');
		//Get user details
		Route::get('/{id}','MarkVilludo\Permission\Controllers\Api\UserController@show');
		//create user
		Route::post('/', 'MarkVilludo\Permission\Controllers\Api\UserController@store');
		//Update user details
		Route::post('/{id}','MarkVilludo\Permission\Controllers\Api\UserController@update');
		
	});

  	Route::get('/users-list', 'MarkVilludo\Permission\Controllers\Api\UserController@getUsers')->name('api.users.list');
  	Route::get('/check-exist-email', 'MarkVilludo\Permission\Controllers\Api\UserController@checkExistEmail')->name('api.check_user_email');

});
