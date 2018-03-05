<?php
namespace Ajaaleixo\Middleware\CorrelationId;

class CorrelationIdProcessor
{
    protected $correlationId = '';

    protected $paramName;

    public function __construct(string $paramName, $correlationId = null)
    {
        $this->paramName = $paramName;
        if ($correlationId !== null) {
            $this->correlationId = (string) $correlationId;
        }
    }

    public function __invoke(array $record): array
    {
        if (!empty($this->correlationId)) {
            $record['context'][$this->paramName] = $this->correlationId;
        }

        return $record;
    }

    public function getCorrelationId(): string
    {
        return $this->correlationId;
    }
}