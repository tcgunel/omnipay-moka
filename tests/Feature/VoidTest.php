<?php

namespace Omnipay\Moka\Tests\Feature;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Moka\Message\VoidRequest;
use Omnipay\Moka\Message\VoidResponse;
use Omnipay\Moka\Tests\TestCase;

class VoidTest extends TestCase
{
    /**
     * @throws \JsonException
     */
    public function test_void_request()
    {
        $options = file_get_contents(__DIR__ . '/../Mock/VoidRequest.json');

        $options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

        $request = new VoidRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($options);

        $data = $request->getData();

        self::assertEquals('TestDealer', $data['PaymentDealerAuthentication']['DealerCode']);

        self::assertEquals('moka-order-123456', $data['PaymentDealerRequest']['VirtualPosOrderId']);
        self::assertEquals('ORDER-12345', $data['PaymentDealerRequest']['OtherTrxCode']);
        self::assertEquals('127.0.0.1', $data['PaymentDealerRequest']['ClientIP']);
        self::assertEquals(2, $data['PaymentDealerRequest']['VoidRefundReason']);
    }

    public function test_void_request_validation_error()
    {
        $options = file_get_contents(__DIR__ . '/../Mock/VoidRequest-ValidationError.json');

        $options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

        $request = new VoidRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($options);

        $this->expectException(InvalidRequestException::class);

        $request->getData();
    }

    public function test_void_response_success()
    {
        $httpResponse = $this->getMockHttpResponse('VoidResponseSuccess.txt');

        $response = new VoidResponse($this->getMockRequest(), $httpResponse);

        $this->assertTrue($response->isSuccessful());

        $this->assertEquals('Success', $response->getCode());
    }

    public function test_void_response_api_error()
    {
        $httpResponse = $this->getMockHttpResponse('VoidResponseApiError.txt');

        $response = new VoidResponse($this->getMockRequest(), $httpResponse);

        $this->assertFalse($response->isSuccessful());

        $this->assertEquals('Islem bulunamadi', $response->getMessage());

        $this->assertEquals('PaymentDealer.DoVoid.InvalidRequest', $response->getCode());
    }
}
