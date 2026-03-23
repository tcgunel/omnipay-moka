<?php

namespace Omnipay\Moka\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;

class VoidRequest extends RemoteAbstractRequest
{
	protected $endpoint = '/PaymentDealer/DoVoid';

	/**
	 * @throws InvalidRequestException
	 */
	public function getData(): array
	{
		$this->validateAll();

		return [
			'PaymentDealerAuthentication' => $this->getAuthenticationData(),
			'PaymentDealerRequest'        => [
				'VirtualPosOrderId' => $this->getVirtualPosOrderId(),
				'OtherTrxCode'      => $this->getTransactionId(),
				'ClientIP'          => $this->getClientIp() ?? '127.0.0.1',
				'VoidRefundReason'  => $this->getVoidRefundReason() ?? 2,
			],
		];
	}

	/**
	 * @throws InvalidRequestException
	 */
	protected function validateAll(): void
	{
		$this->validateMerchantCredentials();

		$this->validate("virtualPosOrderId", "transactionId");
	}

	/**
	 * @param array $data
	 * @return ResponseInterface|VoidResponse
	 */
	public function sendData($data)
	{
		$httpResponse = $this->sendJsonRequest($this->endpoint, $data);

		return $this->createResponse($httpResponse);
	}

	protected function createResponse($data): VoidResponse
	{
		return $this->response = new VoidResponse($this, $data);
	}
}
