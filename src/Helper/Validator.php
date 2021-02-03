<?php

namespace Interkassa\Helper;

use Interkassa\Exception\ValidationFieldException;
use Interkassa\Request\RequestInterface;

class Validator
{
    /**
     * @param RequestInterface $request
     *
     * @throws ValidationFieldException
     */
    public function validateRequiredFields(RequestInterface $request)
    {
        $intersect = array_intersect($request->getRequiredFields(), array_keys($request->getData()));

        if (count($request->getRequiredFields()) != count($intersect)) {
            $missingRequiredFields = array_diff_key($request->getRequiredFields(), $intersect);

            throw new ValidationFieldException('Missing required fields: ' . implode(', ', $missingRequiredFields));
        }
    }
}
