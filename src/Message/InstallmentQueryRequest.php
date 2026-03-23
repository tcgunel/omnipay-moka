<?php

namespace Omnipay\Moka\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Moka\Helpers\Helper;

class InstallmentQueryRequest extends RemoteAbstractRequest
{
	protected $endpoint = '/PaymentDealer/DoCalcPaymentAmount';

	/**
	 * @throws InvalidRequestException
	 */
	public function getData(): array
	{
		$this->validateAll();

		return [
			'PaymentDealerAuthentication' => $this->getAuthenticationData(),
			'PaymentDealerRequest'        => [
				'BinNumber'         => $this->getBinNumber(),
				'Currency'          => Helper::mapCurrency($this->getCurrency()),
				'OrderAmount'       => Helper::formatAmount($this->getAmount()),
				'InstallmentNumber' => $this->getInstallment() ?? 1,
				'IsThreeD'          => $this->getIsThreeD() ?? 0,
			],
		];
	}

	/**
	 * @throws InvalidRequestException
	 */
	protected function validateAll(): void
	{
		$this->validateMerchantCredentials();

		$this->validate("binNumber", "amount", "installment");
	}

	/**
	 * @param array $data
	 * @return ResponseInterface|InstallmentQueryResponse
	 */
	public function sendData($data)
	{
		$httpResponse = $this->sendJsonRequest($this->endpoint, $data);

		return $this->createResponse($httpResponse);
	}

	protected function createResponse($data): InstallmentQueryResponse
	{
		return $this->response = new InstallmentQueryResponse($this, $data);
	}
}
