<?php
namespace App\Providers;

use App\Helpers\PermissionHelper;
use App\Models\Notification;
use App\Models\User;
use App\Observers\NotificationObserver;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */

    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Permission Blade Directives
        Blade::if('permission', function ($permission) {
            return PermissionHelper::hasPermission($permission);
        });

        Blade::if('anypermission', function ($permissions) {
            return PermissionHelper::hasAnyPermission($permissions);
        });

        Blade::if('allpermissions', function ($permissions) {
            return PermissionHelper::hasAllPermissions($permissions);
        });

        Blade::if('role', function ($role) {
            return PermissionHelper::hasRole($role);
        });

        Blade::if('anyrole', function ($roles) {
            return PermissionHelper::hasAnyRole($roles);
        });

        // Permission Helper Functions
        Blade::directive('hasPermission', function ($expression) {
            return "<?php if (App\Helpers\PermissionHelper::hasPermission($expression)): ?>";
        });

        Blade::directive('endhasPermission', function () {
            return "<?php endif; ?>";
        });

        Blade::directive('hasRole', function ($expression) {
            return "<?php if (App\Helpers\PermissionHelper::hasRole($expression)): ?>";
        });

        Blade::directive('endhasRole', function () {
            return "<?php endif; ?>";
        });

        view()->share('locales', config('translatable.locales'));

        date_default_timezone_set('Asia/Riyadh');
        $languages = ['ar', 'en'];
        $lang      = request()->header('lang');

        if ($lang) {
            if (in_array($lang, $languages)) {
                App::setLocale($lang);
            } else {
                App::setLocale('ar');
            }
        }

        if (! session()->has('lang')) {
            session()->put('lang', 'ar');
        }

        if (Schema::hasTable('users')) {
            Artisan::call('schedule:run');
            User::observe(UserObserver::class);

            Notification::observe(NotificationObserver::class);
        }

        if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
            \URL::forceScheme('https');
        }
    }

}
