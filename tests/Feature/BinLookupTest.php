<?php

namespace Omnipay\Moka\Tests\Feature;

use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Moka\Message\BinLookupRequest;
use Omnipay\Moka\Message\BinLookupResponse;
use Omnipay\Moka\Tests\TestCase;

class BinLookupTest extends TestCase
{
    /**
     * @throws \JsonException
     */
    public function test_bin_lookup_request()
    {
        $options = file_get_contents(__DIR__ . '/../Mock/BinLookupRequest.json');

        $options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

        $request = new BinLookupRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($options);

        $data = $request->getData();

        self::assertEquals('TestDealer', $data['PaymentDealerAuthentication']['DealerCode']);
        self::assertEquals('526911', $data['PaymentDealerRequest']['BinNumber']);
    }

    public function test_bin_lookup_request_validation_error()
    {
        $options = file_get_contents(__DIR__ . '/../Mock/BinLookupRequest-ValidationError.json');

        $options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

        $request = new BinLookupRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($options);

        $this->expectException(InvalidCreditCardException::class);

        $request->getData();
    }

    public function test_bin_lookup_request_with_bin_number_param()
    {
        $options = [
            'merchantId' => 'TestDealer',
            'merchantUser' => 'TestUser',
            'merchantPassword' => 'TestPass',
            'binNumber' => '526911',
        ];

        $request = new BinLookupRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($options);

        $data = $request->getData();

        self::assertEquals('526911', $data['PaymentDealerRequest']['BinNumber']);
    }

    public function test_bin_lookup_response_success()
    {
        $httpResponse = $this->getMockHttpResponse('BinLookupResponseSuccess.txt');

        $response = new BinLookupResponse($this->getMockRequest(), $httpResponse);

        $this->assertTrue($response->isSuccessful());

        $this->assertEquals('CreditCard', $response->getCreditType());

        $this->assertEquals(12, $response->getMaxInstallmentNumber());

        $this->assertEquals('Success', $response->getCode());
    }

    public function test_bin_lookup_response_api_error()
    {
        $httpResponse = $this->getMockHttpResponse('BinLookupResponseApiError.txt');

        $response = new BinLookupResponse($this->getMockRequest(), $httpResponse);

        $this->assertFalse($response->isSuccessful());

        $this->assertEquals('BinNumber bos olamaz', $response->getMessage());
    }
}
