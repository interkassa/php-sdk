<?php

namespace Interkassa\Helper;

class Config
{
    const SCI_URL = 'sci.interkassa.com';

    /**
     * @var string
     */
    private $algorithm = 'md5';

    /**
     * @var string
     */
    private $checkoutSecretKey = '';

    /**
     * @return string
     */
    public function getSciUrl(): string
    {
        return 'https://' . self::SCI_URL;
    }

    /**
     * @return string
     */
    public function getAlgorithm(): string
    {
        return $this->algorithm;
    }

    /**
     * @param string $algorithm
     */
    public function setAlgorithm(string $algorithm)
    {
        $this->algorithm = $algorithm;
    }

    /**
     * @return string
     */
    public function getCheckoutSecretKey(): string
    {
        return $this->checkoutSecretKey;
    }

    /**
     * @param string $checkoutSecretKey
     */
    public function setCheckoutSecretKey(string $checkoutSecretKey)
    {
        $this->checkoutSecretKey = $checkoutSecretKey;
    }
}
