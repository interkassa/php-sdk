<?php

namespace Interkassa\Request;

class WithdrawRequest implements RequestInterface
{
    /**
     * @var array
     */
    protected $requiredFields = [
        'amount',
        'purseId',
        'method',
        'currency',
        'useShortAlias',
    ];

    /**
     * @var array
     */
    private $params = [];

    /**
     * Cумма платежа.
     *
     * @param string $amount
     *
     * @return WithdrawRequest
     */
    public function setAmount(string $amount): WithdrawRequest
    {
        return $this->addToParams('amount', $amount);
    }

    /**
     * Массив реквизитов, типа «ключ-значение.
     *
     * @param string $name
     * @param string $value
     *
     * @return $this
     */
    public function setDetail(string $name, string $value): WithdrawRequest
    {
        $this->params['details[' . $name . ']'] = $value;

        return $this;
    }

    /**
     * Идентификатор кошелька, с которого осуществлять вывод.
     *
     * @param string $purseId
     *
     * @return WithdrawRequest
     */
    public function setPurseId(string $purseId): WithdrawRequest
    {
        return $this->addToParams('purseId', $purseId);
    }

    /**
     * Тип расчета суммы платежа.
     *
     * @param string $calculationKey
     *
     * @return WithdrawRequest
     */
    public function setCalcKey(string $calculationKey): WithdrawRequest
    {
        return $this->addToParams('calcKey', $calculationKey);
    }

    /**
     * Тип действия.
     *
     * @param string $action
     *
     * @return WithdrawRequest
     */
    public function setAction(string $action): WithdrawRequest
    {
        return $this->addToParams('action', $action);
    }

    /**
     * Номер выплаты в системе мерчанта, является уникальным относительно кассы.
     *
     * @param string $paymentNumber
     *
     * @return WithdrawRequest
     */
    public function setPaymentNo(string $paymentNumber): WithdrawRequest
    {
        return $this->addToParams('paymentNo', $paymentNumber);
    }

    /**
     * Метод.
     *
     * @param string $method
     *
     * @return WithdrawRequest
     */
    public function setMethod(string $method): WithdrawRequest
    {
        return $this->addToParams('method', $method);
    }

    /**
     * Валюта.
     *
     * @param string $currency
     *
     * @return WithdrawRequest
     */
    public function setCurrency(string $currency): WithdrawRequest
    {
        return $this->addToParams('currency', $currency);
    }

    /**
     * Обязательный системный параметр.
     *
     * @param string $shortAlias
     *
     * @return WithdrawRequest
     */
    public function setUseShortAlias(string $shortAlias): WithdrawRequest
    {
        return $this->addToParams('useShortAlias', $shortAlias);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->params;
    }

    /**
     * @param string $fieldName
     * @param $value
     *
     * @return WithdrawRequest
     */
    private function addToParams(string $fieldName, $value): WithdrawRequest
    {
        $this->params[$fieldName] = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getRequiredFields(): array
    {
        return $this->requiredFields;
    }
}
