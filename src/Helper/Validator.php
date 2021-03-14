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

    public function validateDetailForWithdraw(RequestInterface $request)
    {
        $keys = array_keys($request->getData());
        foreach ($keys as $key) {
            if (preg_match('/detail/', $key) == 1) {
                return;
            }
        }

        throw new ValidationFieldException('Missing required field detail');
    }
}
