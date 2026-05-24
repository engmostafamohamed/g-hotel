<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Events\EmployeeLoggedIn;
use App\Events\EmployeeLoggedOut;
use App\Events\GuestLoggedIn;
use App\Listeners\SendLoginNotification;
use App\Listeners\LogEmployeeLogin;
use App\Listeners\LogEmployeeLogout;
use Illuminate\Support\Facades\File;
use App\Observers\GenericObserver;

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
        //
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/api.php'));

        Route::middleware('api')
            ->prefix('crm')
            ->group(base_path('routes/crm.php'));

        Relation::morphMap([
            'restaurant' => \App\Models\Restaurant::class,
            'service' => \App\Models\Service::class,
        ]);

            $excludedModels = [
                'App\\Models\\Log',
                'App\\Models\\PasswordReset',
            ];

            $modelPath = app_path('Models');
            $models = collect(File::files($modelPath))
                ->map(fn($file) => 'App\\Models\\' . pathinfo($file->getFilename(), PATHINFO_FILENAME));

            foreach ($models as $model) {
                if (class_exists($model) && !in_array($model, $excludedModels)) {
                    $model::observe(GenericObserver::class);
                }
            }
    }
    protected $listen = [
        EmployeeLoggedIn::class => [
            LogEmployeeLogin::class,
        ],
        GuestLoggedIn::class => [
            SendLoginNotification::class,
        ],
        // EmployeeLoggedOut::class => [
        //     LogEmployeeLogout::class,
        // ],
    ];
}
