<?php

namespace Omnipay\Moka\Message;

use JsonException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
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
                    'ResultCode' => 'JsonError',
                    'ResultMessage' => $body,
                    'Data' => null,
                ];

            }

        }
    }

    public function isSuccessful(): bool
    {
        if ($this->isRedirect()) {
            return false;
        }

        return ($this->response['ResultCode'] ?? '') === 'Success'
            && ($this->response['Data']['IsSuccessful'] ?? false) === true;
    }

    public function isRedirect(): bool
    {
        return ($this->response['ResultCode'] ?? '') === 'Success'
            && !empty($this->response['Data']['Url'] ?? null);
    }

    public function getRedirectUrl()
    {
        return $this->response['Data']['Url'] ?? null;
    }

    public function getRedirectMethod(): string
    {
        return 'GET';
    }

    public function getRedirectData(): ?array
    {
        return null;
    }

    public function getTransactionReference(): ?string
    {
        return $this->response['Data']['VirtualPosOrderId'] ?? null;
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
}
