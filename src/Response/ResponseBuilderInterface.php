<?php

namespace Interkassa\Response;

use Interkassa\HttpClient\HttpClientResponse;

interface ResponseBuilderInterface
{
    public function handleError(HttpClientResponse $httpClientResponse);

    public function buildResponse(): InterkassaResponse;
}
