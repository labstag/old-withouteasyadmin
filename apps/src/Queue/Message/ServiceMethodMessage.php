<?php

namespace Labstag\Queue\Message;

class ServiceMethodMessage
{

    protected string $serviceName;

    protected string $method;

    protected array $params;

    public function __construct(
        string $serviceName,
        string $method,
        array $params = []
    )
    {
        $this->serviceName = $serviceName;
        $this->method      = $method;
        $this->params      = $params;
    }

    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getParams(): array
    {
        return $this->params;
    }
}
