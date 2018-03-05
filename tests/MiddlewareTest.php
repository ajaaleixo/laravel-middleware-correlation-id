<?php
namespace Ajaaleixo\Middleware\CorrelationId\Test;

use Ajaaleixo\Middleware\CorrelationId\CorrelationIdMiddleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Webpatser\Uuid\Uuid;

class MiddlewareTest extends TestCase
{
    protected $correlationIdMiddleware;

    public function setUp()
    {
        parent::setUp();

        $this->correlationIdMiddleware = new CorrelationIdMiddleware($this->app);
    }

    /** @test */
    public function has_macros_when_middleware_runs()
    {
        // Prepare
        $request = $this->makeRequestWithCorrelationHeader();

        // Test
        $this->runMiddleware($this->correlationIdMiddleware, $request);

        // Assert
        $this->assertTrue($request->hasMacro('hasCorrelationId'));
        $this->assertTrue($request->hasMacro('getCorrelationId'));
        $this->assertTrue($request->hasMacro('setCorrelationId'));
    }

    /** @test */
    public function correlation_propagates_to_logs()
    {
        // Prepare
        $request = $this->makeRequestWithCorrelationHeader();
        $correlationId = $request->header('x-correlation-id');
        $logMessage = 'This is my n log entry';
        $correlationParam = config('correlationid.param_name');

        // Test
        $this->runMiddleware($this->correlationIdMiddleware, $request);
        Log::info($logMessage);

        $lastLogLine = $this->getLastLogLine();
        $this->assertContains($logMessage, $lastLogLine);
        $this->assertContains($correlationId, $lastLogLine);
        $this->assertContains($correlationParam, $lastLogLine);
    }

    /** @test */
    public function correlation_does_not_propagate_to_logs()
    {
        // Prepare
        $request = $this->makeRequestWithoutCorrelationHeader();
        $this->app['config']->set('correlationid.propagates', false);
        $logMessage = 'This is a log without correlation id';
        $correlationParam = config('correlationid.param_name');

        // Test
        $this->runMiddleware($this->correlationIdMiddleware, $request);
        Log::info($logMessage);

        $lastLogLine = $this->getLastLogLine();
        $this->assertContains($logMessage, $lastLogLine);
        $this->assertNotContains($correlationParam, $lastLogLine);
    }
    
    protected function runMiddleware($middleware, $request)
    {
        return $middleware->handle($request, function () {
            return (new Response())->setContent('<html></html>');
        });
    }

    protected function makeRequestWithCorrelationHeader()
    {
        $request = new Request();
        $request->headers->add([config('correlationid.header_name') => (string) Uuid::generate(4)]);

        return $request;
    }

    protected function makeRequestWithoutCorrelationHeader()
    {
        return new Request();
    }

    protected function getLastLogLine()
    {
        $content = file_get_contents($this->app['config']['logging']['channels']['single']['path']);
        $arrayContent = explode("\n", $content);

        return $arrayContent[count($arrayContent)-2];
    }
}