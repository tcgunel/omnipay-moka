<?php

namespace Omnipay\Moka\Message;

use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Moka\Helpers\Helper;

class PurchaseRequest extends RemoteAbstractRequest
{
	protected $endpoint = '/PaymentDealer/DoDirectPayment';

	protected $endpointThreeD = '/PaymentDealer/DoDirectPaymentThreeD';

	/**
	 * @throws InvalidRequestException
	 * @throws InvalidCreditCardException
	 */
	public function getData(): array
	{
		$this->validateAll();

		$paymentRequest = [
			'CardHolderFullName' => $this->get_card('getName'),
			'CardNumber'         => $this->get_card('getNumber'),
			'ExpMonth'           => Helper::formatExpiryMonth($this->get_card('getExpiryMonth')),
			'ExpYear'            => Helper::formatExpiryYear($this->get_card('getExpiryYear')),
			'CvcNumber'          => $this->get_card('getCvv'),
			'Amount'             => Helper::formatAmount($this->getAmount()),
			'Currency'           => Helper::mapCurrency($this->getCurrency()),
			'InstallmentNumber'  => $this->getInstallment() ?? 1,
			'ClientIP'           => $this->getClientIp() ?? '127.0.0.1',
			'OtherTrxCode'       => $this->getTransactionId(),
			'IsPoolPayment'      => $this->getIsPoolPayment() ?? 0,
			'IsTokenized'        => 0,
			'Software'           => $this->getSoftware() ?? 'omnipay',
			'IsPreAuth'          => $this->getIsPreAuth() ?? 0,
		];

		if ($this->getSecure()) {
			$paymentRequest['ReturnHash']  = 1;
			$paymentRequest['RedirectType'] = 0;
			$paymentRequest['RedirectUrl']  = $this->getReturnUrl();
		}

		return [
			'PaymentDealerAuthentication' => $this->getAuthenticationData(),
			'PaymentDealerRequest'        => $paymentRequest,
		];
	}

	/**
	 * @throws InvalidRequestException
	 * @throws InvalidCreditCardException
	 */
	protected function validateAll(): void
	{
		$this->validateMerchantCredentials();

		$this->getCard()->validate();

		$this->validate("amount", "transactionId");

		if ($this->getSecure()) {
			$this->validate("returnUrl");
		}
	}

	/**
	 * @param array $data
	 * @return ResponseInterface|PurchaseResponse
	 */
	public function sendData($data)
	{
		$path = $this->getSecure() ? $this->endpointThreeD : $this->endpoint;

		$httpResponse = $this->sendJsonRequest($path, $data);

		return $this->createResponse($httpResponse);
	}

	protected function createResponse($data): PurchaseResponse
	{
		return $this->response = new PurchaseResponse($this, $data);
	}
}
