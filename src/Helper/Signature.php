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
    public static function makeSignature(array $data, string $key, $algorithm = 'md5'): string
    {
        $data = self::sortByKeyRecursive($data);
        $data[] = $key;
        $signString = self::implodeRecursive(':', $data);

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
    public static function checkSignature(array $data, string $key, $algorithm = 'md5'): bool
    {
        if (!isset($data['ik_sign'])) {
            return true;
        }

        $signatureFromRequest = $data['ik_sign'];
        unset($data['ik_sign']);

        $signature = self::makeSignature($data, $key, $algorithm);

        return $signatureFromRequest === $signature;
    }

    private static function sortByKeyRecursive(array $array): array
    {
        ksort($array, SORT_STRING);
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = self::sortByKeyRecursive($value);
            }
        }
        return $array;
    }

    private static function implodeRecursive(string $separator, array $array): string
    {
        $result = '';
        foreach ($array as $item) {
            $result .= (is_array($item) ? self::implodeRecursive($separator, $item) : (string) $item) . $separator;
        }

        return substr($result, 0, -1 * strlen($separator));
    }
}
