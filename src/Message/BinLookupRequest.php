<?php

namespace Omnipay\Moka\Message;

use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Moka\Helpers\Helper;

class BinLookupRequest extends RemoteAbstractRequest
{
	protected $endpoint = '/PaymentDealer/GetBankCardInformation';

	/**
	 * @throws InvalidRequestException
	 * @throws InvalidCreditCardException
	 */
	public function getData(): array
	{
		$this->validateAll();

		$binNumber = $this->getBinNumber();

		if (!$binNumber && $this->getCard()) {
			$binNumber = Helper::formatBinNumber($this->getCard()->getNumber());
		}

		return [
			'PaymentDealerAuthentication' => $this->getAuthenticationData(),
			'PaymentDealerRequest'        => [
				'BinNumber' => $binNumber,
			],
		];
	}

	/**
	 * @throws InvalidRequestException
	 * @throws InvalidCreditCardException
	 */
	protected function validateAll(): void
	{
		$this->validateMerchantCredentials();

		if (!$this->getBinNumber() && $this->getCard()) {
			$number = $this->getCard()->getNumber();
			if ($number !== null && !preg_match('/^\d{6,19}$/', $number)) {
				throw new InvalidCreditCardException('Card number should have at least 6 to maximum of 19 digits');
			}
		} elseif (!$this->getBinNumber()) {
			throw new InvalidRequestException('The binNumber parameter or card is required');
		}
	}

	/**
	 * @param array $data
	 * @return ResponseInterface|BinLookupResponse
	 */
	public function sendData($data)
	{
		$httpResponse = $this->sendJsonRequest($this->endpoint, $data);

		return $this->createResponse($httpResponse);
	}

	protected function createResponse($data): BinLookupResponse
	{
		return $this->response = new BinLookupResponse($this, $data);
	}
}
