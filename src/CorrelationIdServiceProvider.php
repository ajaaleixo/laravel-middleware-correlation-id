<?php
namespace Ajaaleixo\Middleware\CorrelationId;

use Illuminate\Support\ServiceProvider;

class CorrelationIdServiceProvider extends ServiceProvider
{
    /**
     * Register the middleware
     */
    public function register()
    {
        $this->app['router']->aliasMiddleware('correlation_id', CorrelationIdMiddleware::class);
    }
}