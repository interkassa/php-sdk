<?php

namespace Interkassa\Response;

class InterkassaResponse
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * InterkassaResponse constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data['data'] ?? [];
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->data['code'] ?? '';
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->data['status'] ?? '';
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->data['message'] ?? '';
    }
}
