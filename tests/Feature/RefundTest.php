<?php

namespace Omnipay\Moka\Tests\Feature;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Moka\Message\RefundRequest;
use Omnipay\Moka\Message\RefundResponse;
use Omnipay\Moka\Tests\TestCase;

class RefundTest extends TestCase
{
    /**
     * @throws \JsonException
     */
    public function test_refund_request()
    {
        $options = file_get_contents(__DIR__ . '/../Mock/RefundRequest.json');

        $options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

        $request = new RefundRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($options);

        $data = $request->getData();

        self::assertEquals('TestDealer', $data['PaymentDealerAuthentication']['DealerCode']);

        self::assertEquals('moka-order-123456', $data['PaymentDealerRequest']['VirtualPosOrderId']);
        self::assertEquals('ORDER-12345', $data['PaymentDealerRequest']['OtherTrxCode']);
        self::assertEquals(9.99, $data['PaymentDealerRequest']['Amount']);
    }

    public function test_refund_request_validation_error()
    {
        $options = file_get_contents(__DIR__ . '/../Mock/RefundRequest-ValidationError.json');

        $options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

        $request = new RefundRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($options);

        $this->expectException(InvalidRequestException::class);

        $request->getData();
    }

    public function test_refund_response_success()
    {
        $httpResponse = $this->getMockHttpResponse('RefundResponseSuccess.txt');

        $response = new RefundResponse($this->getMockRequest(), $httpResponse);

        $this->assertTrue($response->isSuccessful());

        $this->assertEquals('Success', $response->getCode());
    }

    public function test_refund_response_api_error()
    {
        $httpResponse = $this->getMockHttpResponse('RefundResponseApiError.txt');

        $response = new RefundResponse($this->getMockRequest(), $httpResponse);

        $this->assertFalse($response->isSuccessful());

        $this->assertEquals('Islem bulunamadi', $response->getMessage());

        $this->assertEquals('PaymentDealer.DoCreateRefundRequest.InvalidRequest', $response->getCode());
    }
}
