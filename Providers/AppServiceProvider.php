<?php

namespace Modules\CHJumpSeat\Providers;

use App\Contracts\Modules\ServiceProvider;

/**
 * @package $NAMESPACE$
 */
class AppServiceProvider extends ServiceProvider
{
    private $moduleSvc;

    protected $defer = false;

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->moduleSvc = app('App\Services\ModuleService');

        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();

        $this->registerLinks();

        // Uncomment this if you have migrations
        // $this->loadMigrationsFrom(__DIR__ . '/../$MIGRATIONS_PATH$');
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        //
    }

    /**
     * Add module links here
     */
    public function registerLinks(): void
    {
        // Show this link if logged in
        $this->moduleSvc->addFrontendLink('JumpSeat', '/chjumpseat/', '', $logged_in=true);

        // Admin links:
        $this->moduleSvc->addAdminLink('JumpSeat', '/admin/chjumpseat');
    }

    /**
     * Register config.
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('chjumpseat.php'),
        ], 'chjumpseat');

        $this->mergeConfigFrom(__DIR__.'/../Config/config.php', 'chjumpseat');
    }

    /**
     * Register views.
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/chjumpseat');
        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([$sourcePath => $viewPath],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/chjumpseat';
        }, \Config::get('view.paths')), [$sourcePath]), 'chjumpseat');
    }

    /**
     * Register translations.
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/chjumpseat');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'chjumpseat');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'chjumpseat');
        }
    }
}
