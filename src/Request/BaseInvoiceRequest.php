<?php

namespace Interkassa\Request;

class BaseInvoiceRequest implements RequestInterface
{
    /**
     * Prefix for custom fields.
     */
    const PREFIX = 'ik_x_';

    /**
     * Prefix for customer fields.
     */
    const CUSTOMER_PREFIX = 'ik_customer_';

    /**
     * @var array
     */
    protected $requiredFields = [];

    /**
     * @var array
     */
    private $params = [];

    /**
     * @var bool
     */
    private $needSignature = true;

    /**
     * Действие.
     * Позволяет переопределить начальное состояние процесса оплаты.
     * Опциональный параметр.
     * process — обработать;
     * payways — способы оплаты;
     * payway — платежное направление.
     *
     * @param string $value
     *
     * @return BaseInvoiceRequest
     */
    public function setAction(string $value): BaseInvoiceRequest
    {
        return $this->addToParams('ik_act', $value);
    }

    /**
     * Сумма платежа.
     * Обязательный параметр.
     *
     * @param string $value
     *
     * @return BaseInvoiceRequest
     */
    public function setAmount(string $value): BaseInvoiceRequest
    {
        return $this->addToParams('ik_am', $value);
    }

    /**
     * Идентификатор кассы. См. настройки кассы.
     * Обязательный параметр.
     *
     * @param string $value
     *
     * @return BaseInvoiceRequest
     */
    public function setCheckoutId(string $value): BaseInvoiceRequest
    {
        return $this->addToParams('ik_co_id', $value);
    }


    /**
     * Валюта платежа.
     * Обязательный параметр,
     * если к кассе подключено больше чем одна валюта.
     * См. настройки кассы.
     *
     * @param string $value
     *
     * @return BaseInvoiceRequest
     */
    public function setCurrency(string $value): BaseInvoiceRequest
    {
        return $this->addToParams('ik_cur', $value);
    }


    /**
     * Обязательный параметр,
     * 
     * @param string $value
     *
     * @return BaseInvoiceRequest
     */
    public function setPaymentMethod(string $value): BaseInvoiceRequest
    {
        return $this->addToParams('ik_payment_method', $value);
    }

    /**
     * Обязательный параметр,
     * 
     * @param string $value
     *
     * @return BaseInvoiceRequest
     */
    public function setPaymentCurrency(string $value): BaseInvoiceRequest
    {
        return $this->addToParams('ik_payment_currency', $value);
    }

    /**
     * Префикс дополнительных полей.
     *
     * Позволяет передавать дополнительные поля на SCI,
     * после чего эти параметры включаются в данные уведомления
     * о совершенном платеже на страницу взаимодействия.
     *
     * @param string $value
     *
     * @return BaseInvoiceRequest
     */
    public function setCustomField(string $name, string $value): BaseInvoiceRequest
    {
        return $this->addToParams(self::PREFIX . $name, $value);
    }

    /**
     * Информация о плательщике.
     *
     * Позволяет передавать дополнительные поля на SCI c информацией о плательщике
     * (email, phone, first_name, last_name, country...).
     *
     * @param string $value
     *
     * @return BaseInvoiceRequest
     */
    public function setCustomerField(string $name, string $value): BaseInvoiceRequest
    {
        return $this->addToParams(self::CUSTOMER_PREFIX . $name, $value);
    }

    /**
     * Описание платежа.
     * Обязательный параметр.
     *
     * @param string $value
     *
     * @return BaseInvoiceRequest
     */
    public function setDescription(string $value): BaseInvoiceRequest
    {
        return $this->addToParams('ik_desc', $value);
    }

    /**
     * Используется кодировка UTF-8.
     *
     * @param string $value
     *
     * @return BaseInvoiceRequest
     */
    public function setEncoding(string $value): BaseInvoiceRequest
    {
        return $this->addToParams('ik_enc', $value);
    }

    /**
     * Срок истечения платежа.
     * Не позволяет клиенту оплатить платеж позже указанного срока.
     * Если же он совершил оплату,
     * то средства зачисляются ему на лицевой счет в системе Интеркасса.
     * Параметр используется если платеж привязан к заказу,
     * который быстро теряет свою актуальность с истечением времени.
     * Например: онлайн бронирование.
     *
     * Опциональный параметр.
     *
     * @param string $value
     *
     * @return BaseInvoiceRequest
     */
    public function setExpired(string $value): BaseInvoiceRequest
    {
        return $this->addToParams('ik_exp', $value);
    }

    /**
     * Метод запроса страницы непроведенного платежа.
     *
     * Опциональный параметр.
     *
     * @param string $value
     *
     * @return BaseInvoiceRequest
     */
    public function setFailMethod(string $value): BaseInvoiceRequest
    {
        return $this->addToParams('ik_fal_m', $value);
    }

    /**
     * URL страницы непроведенного платежа.
     *
     * Опциональный параметр.
     *
     * @param string $value
     *
     * @return BaseInvoiceRequest
     */
    public function setFailUrl(string $value): BaseInvoiceRequest
    {
        return $this->addToParams('ik_fal_u', $value);
    }

    /**
     * Интерфейс.
     * Позволяет указать формат интерфейса SCI как "web" или "json".
     *
     * По умолчанию "web".
     *
     * @param string $value
     *
     * @return BaseInvoiceRequest
     */
    protected function setInterface(string $value): BaseInvoiceRequest
    {
        return $this->addToParams('ik_int', $value);
    }

    /**
     * Метод запроса страницы взаимодействия.
     *
     * Опциональный параметр.
     *
     * @param string $value
     *
     * @return BaseInvoiceRequest
     */
    public function setInteractionMethod(string $value): BaseInvoiceRequest
    {
        return $this->addToParams('ik_ia_m', $value);
    }

    /**
     * URL страницы взаимодействия.
     *
     * Опциональный параметр.
     *
     * @param string $value
     *
     * @return BaseInvoiceRequest
     */
    public function setIteractionUrl(string $value): BaseInvoiceRequest
    {
        return $this->addToParams('ik_ia_u', $value);
    }

    /**
     * Время жизни платежа.
     * Указывает в секундах срок истечения платежа после его создания.
     * Не используется, если установлен срок истечения платежа (ik_exp).
     * По умолчанию используется свойство кассы "Время жизни платежа" (Payment Lifetime).
     *
     * Опциональный параметр.
     *
     * @param string $value
     *
     * @return BaseInvoiceRequest
     */
    public function setLifetime(string $value): BaseInvoiceRequest
    {
        return $this->addToParams('ik_ltm', $value);
    }

    /**
     * Локаль.
     * Позволяет явно указать язык и регион установленные для клиента.
     * Формируется по шаблону: [language[_territory].
     *
     * По умолчанию определяется автоматически.
     *
     * @param string $value
     *
     * @return BaseInvoiceRequest
     */
    public function setLocale(string $value): BaseInvoiceRequest
    {
        return $this->addToParams('ik_loc', $value);
    }

    /**
     * Параметр предназначен для передачи контактных данных плательщика, например email или телефон.
     * Данные сохраняются в системе вместе c платежом
     * и могут использоваться в отдельных случаях для передачи на платежную систему.
     * Также при включенных настройках доставки уведомлений на сервер мерчанта -
     * этот параметра будет присутствовать в теле нотификации(см. 3.4. Оповещение о платеже).
     *
     * @param string $value
     *
     * @return BaseInvoiceRequest
     */
    public function setPayerContact(string $value): BaseInvoiceRequest
    {
        return $this->addToParams('ik_cli', $value);
    }

    /**
     * Номер платежа.
     * Сохраняется в биллинге Интеркассы.
     * Позволяет идентифицировать платеж в системе,
     * а так же связать с заказами в Вашем биллинге.
     * Проверяется уникальность,
     * если в настройках кассы установлена данная опция.
     *
     * Обязательный параметр.
     *
     * @param string $value
     *
     * @return BaseInvoiceRequest
     */
    public function setPaymentNumber(string $value): BaseInvoiceRequest
    {
        return $this->addToParams('ik_pm_no', $value);
    }

    /**
     * Cпециальный параметр
     * используется для оплаты платежа без участия плательщика (recurrent payment)
     * или с минимальным участием плательщика (one-click payment).
     * Значение для параметра можно получить после успешного платежа
     * в Уведомлении на сервер мерчанта (см. 3.4. Оповещение о платеже),
     * в поле ik_p_card_token (для карт).
     * Данное значение доступно не во всех платежных системах.
     * Для активации и более подробной информации просьба написать на почту merchant@interkassa.com.
     *
     * @param string $value
     *
     * @return BaseInvoiceRequest
     */
    public function setPayToken(string $value): BaseInvoiceRequest
    {
        return $this->addToParams('ik_pay_token', $value);
    }

    /**
     * Отключенные способы оплаты.
     * Позволяет указывать недоступные способы оплаты для клиента.
     *
     * Опциональный параметр.
     *
     * @param string $value
     *
     * @return BaseInvoiceRequest
     */
    public function setPaywayOff(string $value): BaseInvoiceRequest
    {
        return $this->addToParams('ik_pw_off', $value);
    }

    /**
     * Включенные способы оплаты.
     * Позволяет указывать доступные способы оплаты для клиента.
     *
     * Опциональный параметр.
     *
     * @param string $value
     *
     * @return BaseInvoiceRequest
     */
    public function setPaywayOn(string $value): BaseInvoiceRequest
    {
        return $this->addToParams('ik_pw_on', $value);
    }

    /**
     * Выбранный способ оплаты.
     * Позволяет указать точный способ оплаты для клиента.
     * Параметр работает только с параметром действия (ik_act) установленного в "process" или "payway".
     * см. действие (ik_act).
     *
     * Опциональный параметр.
     *
     * @param string $value
     *
     * @return BaseInvoiceRequest
     */
    public function setPaywayVia(string $value): BaseInvoiceRequest
    {
        return $this->addToParams('ik_pw_via', $value);
    }

    /**
     * Метод запроса страницы ожидания проведения платежа.
     *
     * Опциональный параметр.
     *
     * @param string $value
     *
     * @return BaseInvoiceRequest
     */
    public function setPendingMethod(string $value): BaseInvoiceRequest
    {
        return $this->addToParams('ik_pnd_m', $value);
    }

    /**
     * URL страницы ожидания проведения платежа.
     *
     * Опциональный параметр.
     *
     * @param string $value
     *
     * @return BaseInvoiceRequest
     */
    public function setPendingUrl(string $value): BaseInvoiceRequest
    {
        return $this->addToParams('ik_pnd_u', $value);
    }

    /**
     * Цифровая подпись.
     * См. формирования цифровой подписи.
     * Обязательный параметр, если в настройках кассы установлен параметр
     * "Проверять подпись в форме запроса платежа" (Sign Co Required).
     *
     * @param string $value
     *
     * @return BaseInvoiceRequest
     */
    public function setSignature(string $value): BaseInvoiceRequest
    {
        return $this->addToParams('ik_sign', $value);
    }

    /**
     * Аккаунт пользователя в системе мерчанта.
     *
     * @param string $value
     *
     * @return BaseInvoiceRequest
     */
    public function setSubAccountNumber(string $value): BaseInvoiceRequest
    {
        return $this->addToParams('ik_sub_acc_no', $value);
    }

    /**
     * Метод запроса страницы проведенного платежа.
     *
     * Опциональный параметр.
     *
     * @param string $value
     *
     * @return BaseInvoiceRequest
     */
    public function setSuccessMethod(string $value): BaseInvoiceRequest
    {
        return $this->addToParams('ik_suc_m', $value);
    }

    /**
     * URL страницы проведенного платежа.
     *
     * Опциональный параметр.
     *
     * @param string $value
     *
     * @return BaseInvoiceRequest
     */
    public function setSuccessUrl(string $value): BaseInvoiceRequest
    {
        return $this->addToParams('ik_suc_u', $value);
    }

    /**
     * Тип режима.
     *
     * Опциональный параметр.
     *
     * @param string $value
     *
     * @return BaseInvoiceRequest
     */
    public function setMode(string $value): BaseInvoiceRequest
    {
        return $this->addToParams('ik_mode', $value);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->params;
    }

    /**
     * @return array
     */
    public function getRequiredFields(): array
    {
        return $this->requiredFields;
    }

    /**
     * @param bool $needSignature
     */
    public function withSignature(bool $needSignature)
    {
        $this->needSignature = $needSignature;

        if (!$needSignature) {
            $this->requiredFields = array_diff($this->requiredFields, ['ik_sign']);
        }
    }

    /**
     * @param string $sign
     */
    public function addSignatureToData(string $sign)
    {
        $this->params['ik_sign'] = $sign;
    }

    /**
     * @return bool
     */
    public function isNeedSignature(): bool
    {
        return $this->needSignature;
    }


    /**
     * @param string $fieldName
     * @param string $value
     */
    private function addToParams(string $fieldName, string $value): BaseInvoiceRequest
    {
        $this->params[$fieldName] = $value;

        return $this;
    }
}
