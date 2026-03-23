<?php

namespace Omnipay\Moka\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;

class RefundRequest extends RemoteAbstractRequest
{
	protected $endpoint = '/PaymentDealer/DoCreateRefundRequest';

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
				'Amount'            => \Omnipay\Moka\Helpers\Helper::formatAmount($this->getAmount()),
			],
		];
	}

	/**
	 * @throws InvalidRequestException
	 */
	protected function validateAll(): void
	{
		$this->validateMerchantCredentials();

		$this->validate("virtualPosOrderId", "transactionId", "amount");
	}

	/**
	 * @param array $data
	 * @return ResponseInterface|RefundResponse
	 */
	public function sendData($data)
	{
		$httpResponse = $this->sendJsonRequest($this->endpoint, $data);

		return $this->createResponse($httpResponse);
	}

	protected function createResponse($data): RefundResponse
	{
		return $this->response = new RefundResponse($this, $data);
	}
}
