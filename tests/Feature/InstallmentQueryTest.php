<?php

namespace Omnipay\Moka\Tests\Feature;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Moka\Message\InstallmentQueryRequest;
use Omnipay\Moka\Message\InstallmentQueryResponse;
use Omnipay\Moka\Tests\TestCase;

class InstallmentQueryTest extends TestCase
{
    /**
     * @throws \JsonException
     */
    public function test_installment_query_request()
    {
        $options = file_get_contents(__DIR__ . '/../Mock/InstallmentQueryRequest.json');

        $options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

        $request = new InstallmentQueryRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($options);

        $data = $request->getData();

        self::assertEquals('TestDealer', $data['PaymentDealerAuthentication']['DealerCode']);
        self::assertEquals('526911', $data['PaymentDealerRequest']['BinNumber']);
        self::assertEquals('TL', $data['PaymentDealerRequest']['Currency']);
        self::assertEquals(100.00, $data['PaymentDealerRequest']['OrderAmount']);
        self::assertEquals(3, $data['PaymentDealerRequest']['InstallmentNumber']);
        self::assertEquals(0, $data['PaymentDealerRequest']['IsThreeD']);
    }

    public function test_installment_query_request_validation_error()
    {
        $options = file_get_contents(__DIR__ . '/../Mock/InstallmentQueryRequest-ValidationError.json');

        $options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

        $request = new InstallmentQueryRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($options);

        $this->expectException(InvalidRequestException::class);

        $request->getData();
    }

    public function test_installment_query_response_success()
    {
        $httpResponse = $this->getMockHttpResponse('InstallmentQueryResponseSuccess.txt');

        $response = new InstallmentQueryResponse($this->getMockRequest(), $httpResponse);

        $this->assertTrue($response->isSuccessful());

        $this->assertEquals(103.50, $response->getPaymentAmount());

        $this->assertEquals('Success', $response->getCode());
    }

    public function test_installment_query_response_api_error()
    {
        $httpResponse = $this->getMockHttpResponse('InstallmentQueryResponseApiError.txt');

        $response = new InstallmentQueryResponse($this->getMockRequest(), $httpResponse);

        $this->assertFalse($response->isSuccessful());

        $this->assertEquals('BinNumber bos olamaz', $response->getMessage());
    }
}
