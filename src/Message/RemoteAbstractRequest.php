<?php

namespace Omnipay\Moka\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Moka\Helpers\Helper;
use Omnipay\Moka\Traits\PurchaseGettersSetters;

abstract class RemoteAbstractRequest extends AbstractRequest
{
	use PurchaseGettersSetters;

	protected $testEndpoint = 'https://service.refmoka.com';

	protected $liveEndpoint = 'https://service.moka.com';

	/**
	 * @throws InvalidRequestException
	 */
	protected function validateMerchantCredentials(): void
	{
		$this->validate("merchantId", "merchantUser", "merchantPassword");
	}

	protected function getBaseUrl(): string
	{
		return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
	}

	protected function getAuthenticationData(): array
	{
		return [
			'DealerCode' => $this->getMerchantId(),
			'Username'   => $this->getMerchantUser(),
			'Password'   => $this->getMerchantPassword(),
			'CheckKey'   => Helper::generateCheckKey(
				$this->getMerchantId(),
				$this->getMerchantUser(),
				$this->getMerchantPassword()
			),
		];
	}

	protected function get_card($key)
	{
		return $this->getCard() ? $this->getCard()->$key() : null;
	}

	protected function sendJsonRequest(string $path, array $data): \Psr\Http\Message\ResponseInterface
	{
		$url = $this->getBaseUrl() . $path;

		return $this->httpClient->request(
			'POST',
			$url,
			[
				'Content-Type' => 'application/json',
				'Accept'       => 'application/json',
			],
			json_encode($data)
		);
	}

	abstract protected function createResponse($data);
}
