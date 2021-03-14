<?php

namespace Interkassa\Request;

class RefundRequest implements RequestInterface
{
    /**
     * @var array
     */
    protected $requiredFields = [
        'id',
        'amount',
    ];

    /**
     * @var array
     */
    private $params = [];

    /**
     * ID платежа, по которому нужно создать операцию возврата.
     *
     * @param string $id
     *
     * @return RefundRequest
     */
    public function setId(string $id): RefundRequest
    {
        return $this->addToParams('id', $id);
    }

    /**
     * Cумма возврата.
     *
     * @param string $amount
     *
     * @return RefundRequest
     */
    public function setAmount(string $amount): RefundRequest
    {
        return $this->addToParams('amount', $amount);
    }

    /**
     * Идентификатор платежа мерчанта.
     *
     * @param string $merchantOrderId
     *
     * @return RefundRequest
     */
    public function setMerchantOrderId(string $merchantOrderId): RefundRequest
    {
        return $this->addToParams('merchantOrderId', $merchantOrderId);
    }

    /**
     * Описание операции возврата.
     *
     * @param string $description
     *
     * @return RefundRequest
     */
    public function setDescription(string $description): RefundRequest
    {
        return $this->addToParams('description', $description);
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
     * @param string $value
     *
     * @return RefundRequest
     */
    private function addToParams(string $fieldName, string $value): RefundRequest
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
