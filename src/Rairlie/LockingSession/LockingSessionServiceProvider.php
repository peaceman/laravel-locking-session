<?php
namespace Rairlie\LockingSession;

use Illuminate\Session\SessionServiceProvider;

class LockingSessionServiceProvider extends SessionServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../../config/laravel-locking-session.php' => config_path('laravel-locking-session.php'),
        ]);
    }

    /**
     * Override so we return our own Middleware\StartSession
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfig();

        $this->registerSessionManager();

        $this->registerSessionDriver();

        $this->app->singleton('Illuminate\Session\Middleware\StartSession', function ($app) {
            return new Middleware\StartSession($app->make('session'));
        });
    }

    /**
     * Override so we return our own SessionManager
     *
     * @return void
     */
    protected function registerSessionManager()
    {
        $this->app->singleton('session', function ($app) {
            return new SessionManager($app);
        });
    }

    /**
     * @return void
     */
    protected function mergeConfig()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../../config/laravel-locking-session.php', 'laravel-locking-session');
    }

}
