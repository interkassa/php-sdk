<?php

namespace Interkassa\HttpClient;

interface ClientInterface
{
    /**
     * @param string $method
     * @param string $url
     * @param array  $headers
     * @param array  $params
     *
     * @return HttpClientResponse
     */
    public function request(string $method, string $url, array $headers = [], array $params = []): HttpClientResponse;
}
