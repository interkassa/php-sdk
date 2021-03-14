<?php

namespace Interkassa\Helper;

class Config
{
    const SCI_URL = 'sci.interkassa.com';

    const API_URL = 'api.interkassa.com';

    const API_PATH = '/v1';

    /**
     * @var string API key.
     */
    private $authorizationKey = '';

    /**
     * @var string Account ID.
     */
    private $accountId = '';

    /**
     * @var string
     */
    private $algorithm = 'md5';

    /**
     * @var string
     */
    private $checkoutSecretKey = '';

    /**
     * @param string $authorizationKey
     */
    public function setAuthorizationKey(string $authorizationKey)
    {
        $this->authorizationKey = $authorizationKey;
    }

    /**
     * @param string $accountId
     */
    public function setAccountId(string $accountId)
    {
        $this->accountId = $accountId;
    }

    /**
     * @return string
     */
    public function getAuthorizationKey(): string
    {
        return $this->authorizationKey;
    }

    /**
     * @return string
     */
    public function getAccountId(): string
    {
        return $this->accountId;
    }

    /**
     * @return string The API url to use for requests. Default is api.interkassa.com
     */
    public function getApiUrl()
    {
        return 'https://' . self::API_URL . self::API_PATH;
    }

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
