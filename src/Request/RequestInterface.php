<?php

namespace Interkassa\Request;

interface RequestInterface
{
    /**
     * @return array
     */
    public function getData(): array;

    /**
     * @return array
     */
    public function getRequiredFields(): array;
}
