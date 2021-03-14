<?php

namespace Interkassa\HttpClient;

use Interkassa\Exception\ConnectException;
use Interkassa\Exception\HttpClientException;

class HttpCurl implements ClientInterface
{
    /**
     * @var array
     */
    private $options = [
        CURLOPT_CONNECTTIMEOUT => 60,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_HEADER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_SSL_VERIFYPEER => 1,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_USERAGENT => 'interkassa-sdk-v1',
    ];

    /**
     * @inheritDoc
     */
    public function request(string $method, string $url, array $headers = [], array $params = []): HttpClientResponse
    {
        $method = strtoupper($method);
        if (!$this->curlEnabled()) {
            throw new HttpClientException('Curl not enabled.');
        }
        if (!$url) {
            throw new HttpClientException('The url is empty.');
        }

        $ch = curl_init($url);
        foreach ($this->options as $option => $value) {
            curl_setopt($ch, $option, $value);
        }
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        if ($params) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }
        $response = curl_exec($ch);
        if ($response === false) {
            $response = curl_error($ch);
        }
        $httpStatus = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);

        if ($httpStatus === 0) {
            throw new ConnectException('Failed to connect to ' . $url);
        }

        return new HttpClientResponse($httpStatus, trim($response));
    }

    /**
     * @return bool
     */
    private function curlEnabled(): bool
    {
        return function_exists('curl_init');
    }
}
