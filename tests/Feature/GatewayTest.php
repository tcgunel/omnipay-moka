<?php

namespace Omnipay\Moka\Tests\Feature;

use Omnipay\Moka\Message\BinLookupRequest;
use Omnipay\Moka\Message\CompletePurchaseRequest;
use Omnipay\Moka\Message\InstallmentQueryRequest;
use Omnipay\Moka\Message\PurchaseRequest;
use Omnipay\Moka\Message\RefundRequest;
use Omnipay\Moka\Message\VoidRequest;
use Omnipay\Moka\Tests\TestCase;

class GatewayTest extends TestCase
{
    public function test_gateway_name()
    {
        self::assertEquals('Moka', $this->gateway->getName());
    }

    public function test_gateway_default_parameters()
    {
        $defaults = $this->gateway->getDefaultParameters();

        self::assertArrayHasKey('clientIp', $defaults);
        self::assertArrayHasKey('merchantId', $defaults);
        self::assertArrayHasKey('merchantUser', $defaults);
        self::assertArrayHasKey('merchantPassword', $defaults);
        self::assertArrayHasKey('installment', $defaults);
    }

    public function test_gateway_purchase()
    {
        $request = $this->gateway->purchase([
            'merchantId' => 'TestDealer',
            'merchantUser' => 'TestUser',
            'merchantPassword' => 'TestPass',
        ]);

        self::assertInstanceOf(PurchaseRequest::class, $request);
    }

    public function test_gateway_complete_purchase()
    {
        $request = $this->gateway->completePurchase([
            'merchantId' => 'TestDealer',
            'merchantUser' => 'TestUser',
            'merchantPassword' => 'TestPass',
        ]);

        self::assertInstanceOf(CompletePurchaseRequest::class, $request);
    }

    public function test_gateway_void()
    {
        $request = $this->gateway->void([
            'merchantId' => 'TestDealer',
            'merchantUser' => 'TestUser',
            'merchantPassword' => 'TestPass',
        ]);

        self::assertInstanceOf(VoidRequest::class, $request);
    }

    public function test_gateway_refund()
    {
        $request = $this->gateway->refund([
            'merchantId' => 'TestDealer',
            'merchantUser' => 'TestUser',
            'merchantPassword' => 'TestPass',
        ]);

        self::assertInstanceOf(RefundRequest::class, $request);
    }

    public function test_gateway_bin_lookup()
    {
        $request = $this->gateway->binLookup([
            'merchantId' => 'TestDealer',
            'merchantUser' => 'TestUser',
            'merchantPassword' => 'TestPass',
        ]);

        self::assertInstanceOf(BinLookupRequest::class, $request);
    }

    public function test_gateway_installment_query()
    {
        $request = $this->gateway->installmentQuery([
            'merchantId' => 'TestDealer',
            'merchantUser' => 'TestUser',
            'merchantPassword' => 'TestPass',
        ]);

        self::assertInstanceOf(InstallmentQueryRequest::class, $request);
    }
}
