<?php

namespace Interkassa\Response;

use Interkassa\HttpClient\HttpClientResponse;

class ResponseDirector
{
    /**
     * @param ResponseBuilderInterface $builder
     * @param HttpClientResponse       $clientResponse
     *
     * @return InterkassaResponse
     */
    public function build(ResponseBuilderInterface $builder, HttpClientResponse $clientResponse): InterkassaResponse
    {
        $builder->handleError($clientResponse);

        return $builder->buildResponse();
    }
}
