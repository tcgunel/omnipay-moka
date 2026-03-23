<?php

namespace Omnipay\Moka;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Moka\Message\BinLookupRequest;
use Omnipay\Moka\Message\CompletePurchaseRequest;
use Omnipay\Moka\Message\InstallmentQueryRequest;
use Omnipay\Moka\Message\PurchaseRequest;
use Omnipay\Moka\Message\RefundRequest;
use Omnipay\Moka\Message\VoidRequest;
use Omnipay\Moka\Traits\PurchaseGettersSetters;

/**
 * Moka Gateway
 * (c) Tolga Can Gunel
 * http://www.github.com/tcgunel/omnipay-moka
 * @method \Omnipay\Common\Message\NotificationInterface acceptNotification(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = [])
 */
class Gateway extends AbstractGateway
{
	use PurchaseGettersSetters;

	public function getName(): string
	{
		return 'Moka';
	}

	public function getDefaultParameters()
	{
		return [
			"clientIp"         => "127.0.0.1",
			"merchantId"       => "",
			"merchantUser"     => "",
			"merchantPassword" => "",
			"installment"      => 1,
		];
	}

	public function purchase(array $options = []): AbstractRequest
	{
		return $this->createRequest(PurchaseRequest::class, $options);
	}

	public function completePurchase(array $options = []): AbstractRequest
	{
		return $this->createRequest(CompletePurchaseRequest::class, $options);
	}

	public function void(array $options = []): AbstractRequest
	{
		return $this->createRequest(VoidRequest::class, $options);
	}

	public function refund(array $options = []): AbstractRequest
	{
		return $this->createRequest(RefundRequest::class, $options);
	}

	public function binLookup(array $options = []): AbstractRequest
	{
		return $this->createRequest(BinLookupRequest::class, $options);
	}

	public function installmentQuery(array $options = []): AbstractRequest
	{
		return $this->createRequest(InstallmentQueryRequest::class, $options);
	}
}
