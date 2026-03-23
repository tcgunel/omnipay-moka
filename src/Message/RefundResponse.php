<?php

namespace Omnipay\Moka\Message;

use JsonException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class RefundResponse extends AbstractResponse
{
	protected $response;

	protected $request;

	public function __construct(RequestInterface $request, $data)
	{
		parent::__construct($request, $data);

		$this->request = $request;

		$this->response = $data;

		if ($data instanceof ResponseInterface) {

			$body = (string) $data->getBody();

			try {

				$this->response = json_decode($body, true, 512, JSON_THROW_ON_ERROR);

			} catch (JsonException $e) {

				$this->response = [
					'ResultCode'    => 'JsonError',
					'ResultMessage' => $body,
					'Data'          => null,
				];

			}

		}
	}

	public function isSuccessful(): bool
	{
		return ($this->response['ResultCode'] ?? '') === 'Success'
			&& ($this->response['Data']['IsSuccessful'] ?? false) === true;
	}

	public function getMessage(): ?string
	{
		return $this->response['ResultMessage'] ?? null;
	}

	public function getCode(): ?string
	{
		return $this->response['ResultCode'] ?? null;
	}

	public function getData(): array
	{
		return $this->response;
	}

	public function getRedirectData()
	{
		return null;
	}

	public function getRedirectUrl(): string
	{
		return '';
	}
}
