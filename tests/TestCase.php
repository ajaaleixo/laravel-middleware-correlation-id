<?php
namespace Ajaaleixo\Middleware\CorrelationId\Test;

use Ajaaleixo\Middleware\CorrelationId\CorrelationIdServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            CorrelationIdServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('correlationid', [
            'propagates' => true,
            'header_name' => 'X-CORRELATION-ID',
            'param_name' => 'x_correlation_id',
        ]);
    }
}