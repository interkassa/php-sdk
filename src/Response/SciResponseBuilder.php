<?php

namespace Interkassa\Response;

use Interkassa\Exception\AccessDeniedHttpException;
use Interkassa\Exception\HttpClientException;
use Interkassa\Exception\BadRequestException;
use Interkassa\Exception\InternalServerException;
use Interkassa\Exception\UnexpectedException;
use Interkassa\HttpClient\HttpClientResponse;

class SciResponseBuilder implements ResponseBuilderInterface
{
    /**
     * @var array
     */
    private $data;

    /**
     * @param HttpClientResponse $httpClientResponse
     *
     * @throws AccessDeniedHttpException
     * @throws BadRequestException
     * @throws HttpClientException
     */
    public function handleError(HttpClientResponse $httpClientResponse)
    {
        $this->parseResponse($httpClientResponse);

        $httpCode = $httpClientResponse->getHttpCode();
        if (!$this->isOk()) {
            throw new BadRequestException($this->data['resultMsg'], $this->data['resultCode']);
        }

        switch ($httpCode) {
            case 200:
                return;
            case 401:
                throw new AccessDeniedHttpException($this->data['resultMsg']);
            case 500:
                throw new InternalServerException('Internal server error.');
            default:
                throw new UnexpectedException('Unexpected status.');
        }
    }

    /**
     * @param HttpClientResponse $httpClientResponse
     */
    private function parseResponse(HttpClientResponse $httpClientResponse)
    {
        $this->data = json_decode($httpClientResponse->getBody(), true);
    }

    /**
     * @param HttpClientResponse $httpClientResponse
     *
     * @return InterkassaResponse
     */
    public function buildResponse(HttpClientResponse $httpClientResponse): InterkassaResponse
    {
        return new InterkassaResponse([
            'data' => $this->data['resultData'],
            'code' => $this->data['resultCode'],
            'status' => '',
            'message' => $this->data['resultMsg'],
        ]);
    }

    /**
     * @return bool
     */
    public function isOk(): bool
    {
        return $this->data['resultCode'] == '0';
    }
}
