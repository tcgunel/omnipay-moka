<?php

namespace Omnipay\Moka\Tests\Feature;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Moka\Helpers\Helper;
use Omnipay\Moka\Message\PurchaseRequest;
use Omnipay\Moka\Message\PurchaseResponse;
use Omnipay\Moka\Tests\TestCase;

class PurchaseTest extends TestCase
{
	/**
	 * @throws \JsonException
	 */
	public function test_purchase_request()
	{
		$options = file_get_contents(__DIR__ . "/../Mock/PurchaseRequest.json");

		$options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

		$request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());

		$request->initialize($options);

		$data = $request->getData();

		$expectedCheckKey = Helper::generateCheckKey('TestDealer', 'TestUser', 'TestPass');

		self::assertEquals('TestDealer', $data['PaymentDealerAuthentication']['DealerCode']);
		self::assertEquals('TestUser', $data['PaymentDealerAuthentication']['Username']);
		self::assertEquals('TestPass', $data['PaymentDealerAuthentication']['Password']);
		self::assertEquals($expectedCheckKey, $data['PaymentDealerAuthentication']['CheckKey']);

		self::assertEquals('Example User', $data['PaymentDealerRequest']['CardHolderFullName']);
		self::assertEquals('5269111122223332', $data['PaymentDealerRequest']['CardNumber']);
		self::assertEquals('01', $data['PaymentDealerRequest']['ExpMonth']);
		self::assertEquals('2030', $data['PaymentDealerRequest']['ExpYear']);
		self::assertEquals('123', $data['PaymentDealerRequest']['CvcNumber']);
		self::assertEquals(1.50, $data['PaymentDealerRequest']['Amount']);
		self::assertEquals('TL', $data['PaymentDealerRequest']['Currency']);
		self::assertEquals(1, $data['PaymentDealerRequest']['InstallmentNumber']);
		self::assertEquals('127.0.0.1', $data['PaymentDealerRequest']['ClientIP']);
		self::assertEquals('ORDER-12345', $data['PaymentDealerRequest']['OtherTrxCode']);
		self::assertEquals(0, $data['PaymentDealerRequest']['IsPoolPayment']);
		self::assertEquals(0, $data['PaymentDealerRequest']['IsTokenized']);
		self::assertEquals('omnipay', $data['PaymentDealerRequest']['Software']);
		self::assertEquals(0, $data['PaymentDealerRequest']['IsPreAuth']);

		self::assertArrayNotHasKey('ReturnHash', $data['PaymentDealerRequest']);
		self::assertArrayNotHasKey('RedirectType', $data['PaymentDealerRequest']);
		self::assertArrayNotHasKey('RedirectUrl', $data['PaymentDealerRequest']);
	}

	/**
	 * @throws \JsonException
	 */
	public function test_purchase_three_d_request()
	{
		$options = file_get_contents(__DIR__ . "/../Mock/PurchaseThreeDRequest.json");

		$options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

		$request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());

		$request->initialize($options);

		$data = $request->getData();

		self::assertEquals(1, $data['PaymentDealerRequest']['ReturnHash']);
		self::assertEquals(0, $data['PaymentDealerRequest']['RedirectType']);
		self::assertEquals('https://example.com/callback', $data['PaymentDealerRequest']['RedirectUrl']);
	}

	public function test_purchase_request_validation_error()
	{
		$options = file_get_contents(__DIR__ . "/../Mock/PurchaseRequest-ValidationError.json");

		$options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

		$request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());

		$request->initialize($options);

		$this->expectException(InvalidRequestException::class);

		$request->getData();
	}

	public function test_purchase_response_success()
	{
		$httpResponse = $this->getMockHttpResponse('PurchaseResponseSuccess.txt');

		$response = new PurchaseResponse($this->getMockRequest(), $httpResponse);

		$this->assertTrue($response->isSuccessful());

		$this->assertFalse($response->isRedirect());

		$this->assertEquals('moka-order-123456', $response->getTransactionReference());

		$this->assertEquals('Success', $response->getCode());
	}

	public function test_purchase_response_api_error()
	{
		$httpResponse = $this->getMockHttpResponse('PurchaseResponseApiError.txt');

		$response = new PurchaseResponse($this->getMockRequest(), $httpResponse);

		$this->assertFalse($response->isSuccessful());

		$this->assertFalse($response->isRedirect());

		$this->assertEquals('Kart numarasi hatali', $response->getMessage());

		$this->assertEquals('PaymentDealer.DoDirectPayment.InvalidRequest', $response->getCode());
	}

	public function test_purchase_three_d_response_redirect()
	{
		$httpResponse = $this->getMockHttpResponse('PurchaseThreeDResponseSuccess.txt');

		$response = new PurchaseResponse($this->getMockRequest(), $httpResponse);

		$this->assertFalse($response->isSuccessful());

		$this->assertTrue($response->isRedirect());

		$this->assertEquals(
			'https://service.refmoka.com/PaymentPage/3DPayment/abc123def456',
			$response->getRedirectUrl()
		);

		$this->assertEquals('GET', $response->getRedirectMethod());
	}
}
