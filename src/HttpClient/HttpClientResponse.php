<?php

namespace Interkassa\HttpClient;

class HttpClientResponse
{
    /**
     * @var int
     */
    private $httpCode;

    /**
     * @var string
     */
    private $body;

    /**
     * HttpClientResponse constructor.
     *
     * @param int    $httpCode
     * @param string $body
     */
    public function __construct(int $httpCode, string $body)
    {
        $this->httpCode = $httpCode;
        $this->body = $body;
    }

    /**
     * @return int
     */
    public function getHttpCode(): int
    {
        return $this->httpCode;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }
}
