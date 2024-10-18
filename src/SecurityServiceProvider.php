<?php

declare(strict_types=1);

namespace Diviky\Security;

use Diviky\Bright\Support\ServiceProvider;
use Diviky\Security\Console\ClearCommand;
use Illuminate\Support\Facades\Event;

class SecurityServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $events = [
        'Illuminate\Auth\Events\Login' => [
            'Diviky\Security\Listeners\LogSuccessfulLogin',
        ],
        'Illuminate\Auth\Events\Logout' => [
            'Diviky\Security\Listeners\LogSuccessfulLogout',
        ],
        'Illuminate\Auth\Events\Failed' => [
            'Diviky\Security\Listeners\LogFailedLogin',
        ],
    ];

    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        $this->registerEvents();

        $this->loadRoutesFrom($this->path().'/routes/web.php');
        $this->loadViewsFrom($this->path().'/resources/views', 'security');
        $this->loadTranslationsFrom($this->path().'/resources/lang', 'security');

        if ($this->app->runningInConsole()) {
            $this->console();
        }
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom($this->path().'/config/security.php', 'security');
        $this->vendorConfig();
        $this->registerMiddlewares();
    }

    protected function vendorConfig(): void
    {
        $this->mergeConfigFrom($this->path().'/config/google2fa.php', 'google2fa');
        $this->mergeConfigFrom($this->path().'/config/geocoder.php', 'geocoder', true);
        $this->replaceConfigRecursive($this->path().'/config/security-headers.php', 'security-headers');
    }

    protected function path(): string
    {
        return __DIR__.'/..';
    }

    protected function console(): void
    {
        $this->publishes([
            $this->path().'/resources/views' => resource_path('views/vendor/security'),
        ], 'views');

        $this->publishes([
            $this->path().'/database/migrations' => database_path('migrations'),
        ], 'migrations');

        $this->publishes([
            $this->path().'/resources/lang' => resource_path('lang/vendor/security'),
        ], 'translations');

        $this->publishes([
            $this->path().'/config/security.php' => config_path('security.php'),
            $this->path().'/config/security-headers.php' => config_path('security-headers.php'),
        ], 'config');

        $this->commands([
            ClearCommand::class,
        ]);
    }

    /**
     * Register the Authentication Log's events.
     */
    protected function registerEvents(): void
    {
        foreach ($this->events as $event => $listeners) {
            foreach ($listeners as $listener) {
                Event::listen($event, $listener);
            }
        }
    }

    protected function registerMiddlewares(): void
    {
        $router = $this->app['router'];

        $router->aliasMiddleware('2fa', \PragmaRX\Google2FALaravel\Middleware::class);
        $router->aliasMiddleware('2fa.remember', \Diviky\Security\Http\Middleware\Google2FA::class);
        $router->aliasMiddleware('2fa.stateless', \PragmaRX\Google2FALaravel\MiddlewareStateless::class);
        $router->aliasMiddleware('security.password', \Diviky\Security\Http\Middleware\PasswordChange::class);
        $router->aliasMiddleware('security.headers', \Diviky\Security\Http\Middleware\SecureHeadersMiddleware::class);

        $router->pushMiddlewareToGroup('web', 'security.headers');
        $router->pushMiddlewareToGroup('api', 'security.headers');
        $router->pushMiddlewareToGroup('rest', 'security.headers');
    }
}
