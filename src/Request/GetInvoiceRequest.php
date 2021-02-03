<?php

namespace Interkassa\Request;

class GetInvoiceRequest extends BaseInvoiceRequest
{
    /**
     * @var array
     */
    protected $requiredFields = [
        'ik_co_id',
        'ik_pm_no',
        'ik_cur',
        'ik_am',
        'ik_desc',
        'ik_sign',
    ];
}
