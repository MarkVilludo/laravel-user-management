<?php

namespace MarkVilludo\Permission;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use MarkVilludo\Permission\Contracts\Role as RoleContract;
use MarkVilludo\Permission\Contracts\Permission as PermissionContract;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * @param \MarkVilludo\Permission\PermissionRegistrar $permissionLoader
     */
    public function boot(PermissionRegistrar $permissionLoader)
    {   
        $this->loadViewsFrom(__DIR__.'/../views', 'laravel-permission');

        $this->publishes([
           __DIR__.'/../views' => resource_path('/views'),
        ],'views');

        $this->publishes([
            __DIR__.'/../resources/config/laravel-permission.php' => $this->app->configPath().'/'.'laravel-permission.php',
        ], 'config');

        if (! class_exists('CreatePermissionTables')) {
            // Publish the migration
            $timestamp = date('Y_m_d_His', time());
            $this->publishes([
                __DIR__.'/../resources/migrations/create_permission_tables.php.stub' => $this->app->databasePath().'/migrations/'.$timestamp.'_create_permission_tables.php',
            ], 'migrations');
        }

        //publish also assets in public folder for the css and js plugins
         $this->publishes([
           __DIR__.'/../assets' => public_path('/assets'),
        ],'assets');
        //end

        //publish also controllers
        // $this->publishes([
        //   __DIR__.'/../src/Controllers/' => 'app/Http/Controllers/Permissions',
        //],'controllers');
        //end
        
         //publish also models
         $this->publishes([
           __DIR__.'/../src/Models/' => 'app/Models',
        ],'models');
        //end
    
            
        //register routes
        $this->registerRoutes();

        $this->registerModelBindings();

        $permissionLoader->registerPermissions();
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../resources/config/laravel-permission.php',
            'laravel-permission'
        );

        $this->registerBladeExtensions();
    }

    protected function registerModelBindings()
    {
        $config = $this->app->config['laravel-permission.models'];

        $this->app->bind(PermissionContract::class, $config['permission']);
        $this->app->bind(RoleContract::class, $config['role']);
    }

    protected function registerBladeExtensions()
    {
        $this->app->afterResolving('blade.compiler', function (BladeCompiler $bladeCompiler) {
            $bladeCompiler->directive('role', function ($role) {
                return "<?php if(auth()->check() && auth()->user()->hasRole({$role})): ?>";
            });
            $bladeCompiler->directive('endrole', function () {
                return '<?php endif; ?>';
            });

            $bladeCompiler->directive('hasrole', function ($role) {
                return "<?php if(auth()->check() && auth()->user()->hasRole({$role})): ?>";
            });
            $bladeCompiler->directive('endhasrole', function () {
                return '<?php endif; ?>';
            });

            $bladeCompiler->directive('hasanyrole', function ($roles) {
                return "<?php if(auth()->check() && auth()->user()->hasAnyRole({$roles})): ?>";
            });
            $bladeCompiler->directive('endhasanyrole', function () {
                return '<?php endif; ?>';
            });

            $bladeCompiler->directive('hasallroles', function ($roles) {
                return "<?php if(auth()->check() && auth()->user()->hasAllRoles({$roles})): ?>";
            });
            $bladeCompiler->directive('endhasallroles', function () {
                return '<?php endif; ?>';
            });
        });
    }
    protected function registerRoutes()
    {
        include __DIR__.'/routes/api.php';
        include __DIR__.'/routes/web.php';
    }  
}
