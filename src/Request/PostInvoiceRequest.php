<?php

namespace Interkassa\Request;

class PostInvoiceRequest extends BaseInvoiceRequest
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
        'ik_int',
        'ik_sign',
    ];

    /**
     * PostInvoiceRequest constructor.
     */
    public function __construct()
    {
        $this->setInterface('json');
    }
}
