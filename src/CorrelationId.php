<?php
namespace Ajaaleixo\Middleware\CorrelationId;

use \Webpatser\Uuid\Uuid;

class CorrelationId
{
    /**
     * Creates a valid RFC 4122 standard correlation id
     *
     * @return string
     */
    public static function id(): string
    {
        return (string) Uuid::generate(4);
    }

    /**
     * @return string
     */
    public static function getHeaderName(): string
    {
        return config('correlationid.header_name');
    }

    /**
     * @return string
     */
    public static function getParamName(): string
    {
        return config('correlationid.param_name');;
    }
}