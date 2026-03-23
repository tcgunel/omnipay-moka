<?php

namespace Omnipay\Moka\Tests\Feature;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Moka\Message\CompletePurchaseRequest;
use Omnipay\Moka\Message\CompletePurchaseResponse;
use Omnipay\Moka\Tests\TestCase;

class CompletePurchaseTest extends TestCase
{
	/**
	 * @throws \JsonException
	 */
	public function test_complete_purchase_request()
	{
		$options = file_get_contents(__DIR__ . "/../Mock/CompletePurchaseRequest.json");

		$options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

		$request = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());

		$request->initialize($options);

		$data = $request->getData();

		self::assertEquals('ORDER-3D-12345', $data['callback']['otherTrxCode']);
		self::assertEquals('moka-trx-code-789', $data['callback']['trxCode']);
		self::assertNull($data['callback']['resultCode']);
		self::assertNull($data['callback']['resultMessage']);

		self::assertEquals('TestDealer', $data['verification']['PaymentDealerAuthentication']['DealerCode']);
	}

	public function test_complete_purchase_request_validation_error()
	{
		$options = file_get_contents(__DIR__ . "/../Mock/CompletePurchaseRequest-ValidationError.json");

		$options = json_decode($options, true, 512, JSON_THROW_ON_ERROR);

		$request = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());

		$request->initialize($options);

		$this->expectException(InvalidRequestException::class);

		$request->getData();
	}

	public function test_complete_purchase_response_success()
	{
		$httpResponse = $this->getMockHttpResponse('CompletePurchaseResponseSuccess.txt');

		$response = new CompletePurchaseResponse($this->getMockRequest(), $httpResponse);

		$this->assertTrue($response->isSuccessful());

		$this->assertEquals('Success', $response->getCode());
	}

	public function test_complete_purchase_response_api_error()
	{
		$httpResponse = $this->getMockHttpResponse('CompletePurchaseResponseApiError.txt');

		$response = new CompletePurchaseResponse($this->getMockRequest(), $httpResponse);

		$this->assertFalse($response->isSuccessful());

		$this->assertEquals('Islem bulunamadi', $response->getMessage());
	}

	public function test_complete_purchase_callback_error()
	{
		$options = [
			'merchantId'       => 'TestDealer',
			'merchantUser'     => 'TestUser',
			'merchantPassword' => 'TestPass',
			'testMode'         => true,
			'otherTrxCode'     => 'ORDER-3D-12345',
			'trxCode'          => 'moka-trx-code-789',
			'resultCode'       => 'PaymentDealer.DoDirectPaymentThreeD.PaymentFailed',
			'resultMessage'    => '3D dogrulama basarisiz',
		];

		$request = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());

		$request->initialize($options);

		/** @var CompletePurchaseResponse $response */
		$response = $request->send();

		$this->assertFalse($response->isSuccessful());

		$this->assertEquals('3D dogrulama basarisiz', $response->getMessage());
	}
}
