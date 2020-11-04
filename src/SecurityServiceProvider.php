<?php

namespace Diviky\Security;

use Diviky\Bright\Support\ServiceProvider;
use Diviky\Security\Console\ClearCommand;
use Illuminate\Support\Facades\Event;

class SecurityServiceProvider extends ServiceProvider
{
    protected $events = [
        'Illuminate\Auth\Events\Login'  => [
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
    public function boot()
    {
        $this->registerEvents();

        $this->loadRoutesFrom($this->path() . '/routes/web.php');
        $this->loadViewsFrom($this->path() . '/resources/views', 'security');
        $this->loadTranslationsFrom($this->path() . '/resources/lang', 'security');

        if ($this->app->runningInConsole()) {
            $this->console();
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom($this->path() . '/config/security.php', 'security');
        $this->vendorConfig();
        $this->registerMiddlewares();
    }

    protected function vendorConfig()
    {
        $this->mergeConfigFrom($this->path() . '/config/google2fa.php', 'google2fa');
        $this->mergeConfigFrom($this->path() . '/config/firewall.php', 'firewall', true);
        $this->mergeConfigFrom($this->path() . '/config/geocoder.php', 'geocoder', true);
        $this->replaceConfigRecursive($this->path() . '/config/security-headers.php', 'security-headers');
    }

    protected function path()
    {
        return __DIR__ . '/..';
    }

    protected function console()
    {
        $this->publishes([
            $this->path() . '/resources/views' => resource_path('views/vendor/security'),
        ], 'views');

        $this->publishes([
            $this->path() . '/database/migrations' => database_path('migrations'),
        ], 'migrations');

        $this->publishes([
            $this->path() . '/resources/lang' => resource_path('lang/vendor/security'),
        ], 'translations');

        $this->publishes([
            $this->path() . '/config/security.php'         => config_path('security.php'),
            $this->path() . '/config/security-headers.php' => config_path('security-headers.php'),
            $this->path() . '/config/firewall.php'         => config_path('firewall.php'),
        ], 'config');

        $this->commands([
            ClearCommand::class,
        ]);
    }

    /**
     * Register the Authentication Log's events.
     */
    protected function registerEvents()
    {
        foreach ($this->events as $event => $listeners) {
            foreach ($listeners as $listener) {
                Event::listen($event, $listener);
            }
        }
    }

    protected function registerMiddlewares()
    {
        $router = $this->app['router'];

        $router->aliasMiddleware('2fa', \PragmaRX\Google2FALaravel\Middleware::class);
        $router->aliasMiddleware('2fa.remember', \Diviky\Security\Http\Middleware\Google2FA::class);
        $router->aliasMiddleware('2fa.stateless', \PragmaRX\Google2FALaravel\MiddlewareStateless::class);
        $router->aliasMiddleware('firewall.blacklist', \PragmaRX\Firewall\Middleware\FirewallBlacklist::class);
        $router->aliasMiddleware('firewall.whitelist', \PragmaRX\Firewall\Middleware\FirewallWhitelist::class);
        $router->aliasMiddleware('firewall.attacks', \PragmaRX\Firewall\Middleware\BlockAttacks::class);
        $router->aliasMiddleware('security.password', \App\Http\Middleware\PasswordChange::class);
        $router->aliasMiddleware('security.headers', \Diviky\Security\Http\Middleware\SecureHeadersMiddleware::class);

        $router->pushMiddlewareToGroup('firewall', 'firewall.blacklist');
        $router->pushMiddlewareToGroup('firewall', 'firewall.whitelist');
        $router->pushMiddlewareToGroup('firewall', 'firewall.attacks');

        $router->pushMiddlewareToGroup('web', 'security.headers');
        $router->pushMiddlewareToGroup('api', 'security.headers');
        $router->pushMiddlewareToGroup('rest', 'security.headers');
    }
}
