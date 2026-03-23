<?php

namespace Omnipay\Moka\Constants;

class PaymentStatus
{
    /**
     * Odeme basarili
     */
    public const SUCCESS = 2;

    /**
     * Odeme basarisiz
     */
    public const FAILED = 0;

    /**
     * Odeme beklemede
     */
    public const PENDING = 1;
}
