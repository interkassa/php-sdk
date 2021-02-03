<?php

namespace Interkassa\Helper;

class Signature
{
    /**
     * @param array  $data
     * @param string $key
     * @param string $algorithm
     *
     * @return string
     */
    public function makeSignature(array $data, string $key, $algorithm = 'md5'): string
    {
        ksort($data, SORT_STRING);
        array_push($data, $key);
        $signString = implode(':', $data);

        return $algorithm === 'md5'
            ? base64_encode(md5($signString, true))
            : base64_encode(hash('sha256', $signString, true));
    }

    /**
     * @param array  $data
     * @param string $key
     * @param string $algorithm
     *
     * @return bool
     */
    public function checkSignature(array $data, string $key, $algorithm = 'md5'): bool
    {
        if (!isset($data['ik_sign'])) {
            return true;
        }

        $signatureFromRequest = $data['ik_sign'];
        unset($data['ik_sign']);

        $signature = $this->makeSignature($data, $key, $algorithm);

        return $signatureFromRequest === $signature;
    }
}
