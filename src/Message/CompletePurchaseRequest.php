<?php

namespace Omnipay\Moka\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;

class CompletePurchaseRequest extends RemoteAbstractRequest
{
    protected $endpoint = '/PaymentDealer/GetDealerPaymentTrxDetailList';

    /**
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $this->validateAll();

        $callbackData = [
            'otherTrxCode' => $this->getOtherTrxCode(),
            'trxCode' => $this->getTrxCode(),
            'resultCode' => $this->getResultCode(),
            'resultMessage' => $this->getResultMessage(),
        ];

        $verificationRequest = [
            'PaymentDealerAuthentication' => $this->getAuthenticationData(),
            'PaymentDealerRequest' => [
                'PaymentDealerToken' => '',
            ],
        ];

        return [
            'callback' => $callbackData,
            'verification' => $verificationRequest,
        ];
    }

    /**
     * @throws InvalidRequestException
     */
    protected function validateAll(): void
    {
        $this->validateMerchantCredentials();

        $this->validate('otherTrxCode');
    }

    /**
     * @param array $data
     * @return ResponseInterface|CompletePurchaseResponse
     */
    public function sendData($data)
    {
        $callback = $data['callback'];

        if (!empty($callback['resultCode'])) {
            return $this->response = new CompletePurchaseResponse($this, [
                'ResultCode' => 'PaymentDealer.DoDirectPaymentThreeD.PaymentFailed',
                'ResultMessage' => $callback['resultMessage'] ?? $callback['resultCode'],
                'Data' => null,
            ]);
        }

        $httpResponse = $this->sendJsonRequest($this->endpoint, $data['verification']);

        return $this->createResponse($httpResponse);
    }

    protected function createResponse($data): CompletePurchaseResponse
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }
}
