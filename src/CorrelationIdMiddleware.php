<?php
namespace Ajaaleixo\Middleware\CorrelationId;

use Closure;
use Illuminate\Foundation\Application;
use Psr\Log\LoggerInterface;
use Monolog\Logger as MonologLogger;
use Illuminate\Log\Logger as IlluminateLogger;
use Illuminate\Http\Request;

class CorrelationIdMiddleware
{
    protected $logger;

    public function __construct(Application $application)
    {
        /* @var $logger LoggerInterface */
        $this->logger = $application->get('log');

        if (!Request::hasMacro('hasCorrelationId')) {
            Request::macro('hasCorrelationId', function() {
                if ($this->headers->has(CorrelationId::getHeaderName())) {
                    return true;
                }
                return false;
            });
        }
        if (!Request::hasMacro('getCorrelationId')) {
            Request::macro('getCorrelationId', function($default = null) {
                if ($this->headers->has(CorrelationId::getHeaderName())) {
                    return $this->headers->get(CorrelationId::getHeaderName());
                }
                return $default;
            });
        }
        if (!Request::hasMacro('setCorrelationId')) {
            Request::macro('setCorrelationId', function($cid)  {
                $this->headers->set(CorrelationId::getHeaderName(), (string) $cid);
                return $this;
            });
        }
    }

    /**
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the request header already has a correlation id header
        if (!$request->headers->has(CorrelationId::getHeaderName())) {
            $request->headers->set(CorrelationId::getHeaderName(), (string)CorrelationId::id());
        }

        if (!config('correlationid.propagates')) {
            return $next($request);
        }

        $processor = new CorrelationIdProcessor(
            CorrelationId::getParamName(),
            $request->headers->get(CorrelationId::getHeaderName())
        );

        if ($this->logger instanceof MonologLogger) {
            $this->logger->pushProcessor($processor);
        } elseif (method_exists($this->logger, 'getMonolog')) {
            $this->logger->getMonolog()->pushProcessor($processor);
        } elseif ($this->logger->driver() instanceof IlluminateLogger) {
            $logger = $this->logger->driver()->getLogger();
            if ($logger instanceof MonologLogger) {
                $this->logger->pushProcessor($processor);
            }
        }

        return $next($request);
    }
}