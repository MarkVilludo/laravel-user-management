# Associate users with roles and permissions

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-permission.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-permission)
[![Build Status](https://img.shields.io/travis/spatie/laravel-permission/master.svg?style=flat-square)](https://travis-ci.org/spatie/laravel-permission)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-permission.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-permission)
[![StyleCI](https://styleci.io/repos/42480275/shield)](https://styleci.io/repos/42480275)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-permission.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-permission)

This package allows to save permissions and roles in a database. It is built upon [Laravel's
authorization functionality](http://laravel.com/docs/5.1/authorization) that
was [introduced in version 5.1.11](http://christoph-rumpel.com/2015/09/new-acl-features-in-laravel/).

Once installed you can do stuff like this:
```php

// Assign role to specific user
$user->assignRole('writer');

```

You can test if a user has a permission with specific permission and module name.
```php
$user->canAccess('View','Users');
```

## Licensed

You're free to use this package (it's [MIT-licensed](LICENSE.md)), but if it makes it to your production environment we highly appreciate you sending us a message.

## Installation

You can install the package via Composer:
``` bash
composer require mark-villudo/laravel-user-management
```

Now add the service provider in `config/app.php` file:
```php
'providers' => [
    // ...
    MarkVilludo\Permission\PermissionServiceProvider::class,
];
```

You can publish the migration with:
```bash
php artisan vendor:publish --provider="MarkVilludo\Permission\PermissionServiceProvider" --tag="migrations"
```

The package assumes that your users table name is called "users". If this is not the case
you should manually edit the published migration to use your custom table name.

After the migration has been published you can create the role- and permission-tables by
running the migrations:

```bash
$ php artisan migrate
```

You can publish Initial Permission Seeder, then update it's content depending on your needs.
```bash
php artisan vendor:publish --provider="MarkVilludo\Permission\PermissionServiceProvider" --tag="seeder"
```

Content like this.
```
<?php

use Illuminate\Database\Seeder;
use MarkVilludo\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
 	public function run()
    {	
        //web guard
    	$permissions = [
			['name' => 'View', 'module' => 'Teams', 'created_at' => date('Y-m-d H:i:s')],
			['name' => 'Add', 'module' => 'Teams', 'created_at' => date('Y-m-d H:i:s')],
			['name' => 'Edit', 'module' => 'Teams', 'created_at' => date('Y-m-d H:i:s')],
			['name' => 'Delete', 'module' => 'Teams', 'created_at' => date('Y-m-d H:i:s')],
			['name' => 'View', 'module' => 'Players', 'created_at' => date('Y-m-d H:i:s')],
			['name' => 'Add', 'module' => 'Players', 'created_at' => date('Y-m-d H:i:s')],
			['name' => 'Edit', 'module' => 'Players', 'created_at' => date('Y-m-d H:i:s')],
			['name' => 'Delete', 'module' => 'Players', 'created_at' => date('Y-m-d H:i:s')],
			['name' => 'View', 'module' => 'Users', 'created_at' => date('Y-m-d H:i:s')],
			['name' => 'Add', 'module' => 'Users', 'created_at' => date('Y-m-d H:i:s')],
			['name' => 'Edit', 'module' => 'Users', 'created_at' => date('Y-m-d H:i:s')],
			['name' => 'Delete', 'module' => 'Users', 'created_at' => date('Y-m-d H:i:s')],
			['name' => 'View', 'module' => 'Schedules', 'created_at' => date('Y-m-d H:i:s')],
			['name' => 'Add', 'module' => 'Schedules', 'created_at' => date('Y-m-d H:i:s')],
			['name' => 'Edit', 'module' => 'Schedules', 'created_at' => date('Y-m-d H:i:s')],
			['name' => 'Delete', 'module' => 'Schedules', 'created_at' => date('Y-m-d H:i:s')]
    	];

		foreach ($permissions as $permission) {
			$checkIfExist = Permission::where('name', $permission['name'])
									  ->where('module', $permission['module'])
									  ->first();

			if (!$checkIfExist) {
				Permission::insert($permission);
			}
		}
    }
}


```

You can publish the config-file with:
```bash
php artisan vendor:publish --provider="MarkVilludo\Permission\PermissionServiceProvider" --tag="config"
```

This is the contents of the published `config/laravel-permission.php` config file:
```php
return [

    'models' => [

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your permissions. Of course, it
         * is often just the "Permission" model but you may use whatever you like.
         *
         * The model you want to use as a Permission model needs to implement the
         * `MarkVilludo\Permission\Contracts\Permission` contract.
         */

        'permission' => MarkVilludo\Permission\Models\Permission::class,

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your roles. Of course, it
         * is often just the "Role" model but you may use whatever you like.
         *
         * The model you want to use as a Role model needs to implement the
         * `MarkVilludo\Permission\Contracts\Role` contract.
         */

        'role' => MarkVilludo\Permission\Models\Role::class,

    ],

    'table_names' => [

        /*
         * The table that your application uses for users. This table's model will
         * be using the "HasRoles" and "HasPermissions" traits.
         */

        'users' => 'users',

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your roles. We have chosen a basic
         * default value but you may easily change it to any table you like.
         */

        'roles' => 'roles',

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your permissions. We have chosen a basic
         * default value but you may easily change it to any table you like.
         */

        'permissions' => 'permissions',

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your users permissions. We have chosen a
         * basic default value but you may easily change it to any table you like.
         */

        'user_has_permissions' => 'user_has_permissions',

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your users roles. We have chosen a
         * basic default value but you may easily change it to any table you like.
         */

        'user_has_roles' => 'user_has_roles',

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your roles permissions. We have chosen a
         * basic default value but you may easily change it to any table you like.
         */

        'role_has_permissions' => 'role_has_permissions',
    ],

    'foreign_keys' => [
        
        /*
         * The name of the foreign key to the users table.
         */
        'users' => 'user_id',
    ],

    /*
     *
     * By default we'll make an entry in the application log when the permissions
     * could not be loaded. Normally this only occurs while installing the packages.
     *
     * If for some reason you want to disable that logging, set this value to false.
     */

    'log_registration_exception' => true,
];
```

You can publish the views with:
```bash
php artisan vendor:publish --provider="MarkVilludo\Permission\PermissionServiceProvider" --tag="views"
```

You can publish the public assets with:
```bash
php artisan vendor:publish --provider="MarkVilludo\Permission\PermissionServiceProvider" --tag="assets"
```

## Usage

First add the `MarkVilludo\Permission\Traits\HasRoles` trait to your User model, then paste the ff code below.
```php
<?php

namespace App;


use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use MarkVilludo\Permission\Traits\HasRoles;
use MarkVilludo\Permission\Models\Permission;
use MarkVilludo\Permission\Models\RoleHasPermission;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'is_expire_access', 'expiration_date',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function scopeFilterByName($query, $key)
    {
        return $query->where('first_name', 'like', '%' . $key . '%')
            ->orWhere('last_name', 'like', '%' . $key . '%')
            ->orWhere('email', 'like', '%' . $key . '%');
    }

    //get by roles
    public function scopeFilterByRole($query, $role)
    {
        if ($role) {
            return  $query->withAndWhereHas('roles', function ($query) use ($role) {
                    $query->where('id', $role);
            });
        }
    }

    //for withandwherehas
    public function scopeWithAndWhereHas($query, $relation, $constraint)
    {
        return $query->whereHas($relation, $constraint)->with([$relation => $constraint]);
    }
    
    public static function checkAccess($permissionName, $moduleName)
    {
        // return $permissionName.'-'.$moduleName;
        $roleIds = auth()->user()->roles->pluck('id');
        //get permission id base on permission and module name
        $permissionData = Permission::where('name', $permissionName)
                                        ->where('module', $moduleName)
                                        ->first();
        if ($permissionData) {
            $checkIfHasPermission = RoleHasPermission::whereIn('role_id', $roleIds)
                                        ->where('permission_id', $permissionData->id)
                                        ->first();

            if ($checkIfHasPermission) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}


```
## Setup ``env``
We send password in add new user. \n So we need to Define each mail driver, username, password, encryption and mail from. ex:

```
MAIL_DRIVER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=SG.QSaTRD4xQkSULUbbZbF1yg.oDr7zwINfMbaLtvNHFToUYj35ZXxqq6l-SXUN1TpBFs123
MAIL_ENCRYPTION=tls
MAIL_FROM=mark.villudo@synergy88digital.com

```

## Setup User Resource: `php artisan make: resource UserResource` 

Define each return data from user table.
```
  return 
        [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
	    'type' => $this->type, //nullable for special purposes.
            'expiration_date' => date('M d, Y', strtotime($this->expiration_date)),
            'email_verified_at' => $this->email_verified_at,
            'is_expire_access' => $this->is_expire_access,
            'created_at' => $this->created_at->format('M d, Y') .' / '.$this->created_at->format('h:i a'),
            'roles' => $this->roles->pluck('name')
        ];

```

## Setup Role Resource: `php artisan make: resource RoleResource` 

Define each return data from roles table and it's permissions.
```
  return 
        [
            'id' => $this->id,
            'name' => $this->name,
            'permissions' => $this->permissions
        ];
```

## Access User Management pages 
To access the package controller by placing another \ in front you tell Laravel to start searching in the root namespace.

```

//include this part in your routes/web.php
Route::group(['middleware' => 'auth'], function() {

	Route::resource('users', '\MarkVilludo\Permission\Controllers\UserController');
	Route::resource('roles', '\MarkVilludo\Permission\Controllers\RoleController');
	Route::resource('permissions', '\MarkVilludo\Permission\Controllers\PermissionController');
});

```
This package allows for users to be associated with roles. Permissions can be associated with roles.
A `Role` and a `Permission` are regular Eloquent models. Role can have a name and can be created like this:

```php
use MarkVilludo\Permission\Models\Role;

$role = Role::create(['name' => 'writer']);

```

The `HasRoles` adds Eloquent relationships to your models, which can be accessed directly or used as a base query:

```php
$permissions = $user->permissions;
$roles = $user->roles()->pluck('name'); // Returns a collection
```

The `HasRoles` also adds a scope to your models to scope the query to certain roles:

```php
$users = User::role('writer')->get(); // Only returns users with the role 'writer'
```
The scope can accept a string, a `MarkVilludo\Permission\Models\Role` object or an `\Illuminate\Support\Collection` object.

### Using permissions check if can access in specific permission ``Add`` in ``Users`` module.

```php
$user->canAccess('Add','Users');
```

### Using roles and permissions
A role can be assigned to a user:
```php
$user->assignRole('writer');

// You can also assign multiple roles at once
$user->assignRole('writer', 'admin');
$user->assignRole(['writer', 'admin']);
```

A role can be removed from a user:
```php
$user->removeRole('writer');
```

Roles can also be synced:
```php
// All current roles will be removed from the user and replace by the array given
$user->syncRoles(['writer', 'admin']);
```

You can determine if a user has a certain role:
```php
$user->hasRole('writer');
```

You can also determine if a user has any of a given list of roles:
```php
$user->hasAnyRole(Role::all());
```

You can also determine if a user has all of a given list of roles:
```php
$user->hasAllRoles(Role::all());
```

The `assignRole`, `hasRole`, `hasAnyRole`, `hasAllRoles`  and `removeRole` functions can accept a
 string, a `MarkVilludo\Permission\Models\Role` object or an `\Illuminate\Support\Collection` object.

A permission can be given to a role:
```php
$role->givePermissionTo('edit articles');
```

You can determine if a role has a certain permission:
```php
$role->hasPermissionTo('edit articles');
```

A permission can be revoked from a role:
```php
$role->revokePermissionTo('edit articles');
```

You can list all of theses permissions:
```php
// Direct permissions
$user->getDirectPermissions() // Or $user->permissions;

// Permissions inherited from user's roles
$user->getPermissionsViaRoles();

// All permissions which apply on the user
$user->getAllPermissions();
```

All theses responses are collections of `Spatie\Permission\Models\Permission` objects.

If we follow the previous example, the first response will be a collection with the 'delete article' permission, the second will be a collection with the 'edit article' permission and the third will contain both.

### Using Blade directives
This package also adds Blade directives to verify whether the
currently logged in user has all or any of a given list of roles.

```php
@role('writer')
    I'm a writer!
@else
    I'm not a writer...
@endrole
```

```php
@hasrole('writer')
    I'm a writer!
@else
    I'm not a writer...
@endhasrole
```

```php
@hasanyrole(Role::all())
    I have one or more of these roles!
@else
    I have none of these roles...
@endhasanyrole
```

```php
@hasallroles(Role::all())
    I have all of these roles!
@else
    I don't have all of these roles...
@endhasallroles
```

```php
@if(auth()->user()->checkAccess('View', 'Users'))
    <button class="btn btn-success btn-custom waves-effect w-md waves-light m-b-5 pull-left">View User</button>
@endif

```

You can use Laravel's native `@can` directive to check if a user has a certain permission.

## Using a middleware
The package doesn't contain a middleware to check permissions but it's very trivial to add this yourself:
``` bash
$ php artisan make:middleware RoleMiddleware
```

This will create a `app/Http/Middleware/RoleMiddleware.php` file for you, where you can handle your role and permissions check:
```php
use Auth;

// ...

public function handle($request, Closure $next, $role, $permission)
{
    if (Auth::guest()) {
        return redirect($urlOfYourLoginPage);
    }

    if (! $request->user()->hasRole($role)) {
       abort(403);
    }
    
    return $next($request);
}
```

Don't forget to add the route middleware to `app/Http/Kernel.php` file:
```php
protected $routeMiddleware = [
    // ...
    'role' => \App\Http\Middleware\RoleMiddleware::class,
    // ...
];
```

Now you can protect your routes using the middleware you just set up:
```php
Route::group(['middleware' => ['role:admin,access_backend']], function () {
    //
});
```

## Extending

If you need to extend or replace the existing `Role` or `Permission` models you just need to 
keep the following things in mind:

- Your `Role` model needs to implement the `MarkVilludo\Permission\Contracts\Role` contract
- Your `Permission` model needs to implement the `MarkVilludo\Permission\Contracts\Permission` contract
- You must publish the configuration with this command:
  ```bash
  $ php artisan vendor:publish --provider="MarkVilludo\Permission\PermissionServiceProvider" --tag="config"
  ```
  And update the `models.role` and `models.permission` values

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email [mark.villudo@synergy88digital.com]

## Credits
-  Spatie
- [Freek Van der Herten](https://github.com/freekmurze)

This package is heavily based on [Jeffrey Way](https://twitter.com/jeffrey_way)'s awesome [Laracasts](https://laracasts.com) lessons
on [roles and permissions](https://laracasts.com/series/whats-new-in-laravel-5-1/episodes/16). His original code
can be found [in this repo on GitHub](https://github.com/laracasts/laravel-5-roles-and-permissions-demo).

## Alternatives

- [JosephSilber/bouncer](https://github.com/JosephSilber/bouncer)
- [BeatSwitch/lock-laravel](https://github.com/BeatSwitch/lock-laravel)
- [Zizaco/entrust](https://github.com/Zizaco/entrust)
- [bican/roles](https://github.com/romanbican/roles)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
