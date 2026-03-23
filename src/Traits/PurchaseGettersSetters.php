<?php

namespace Omnipay\Moka\Traits;

trait PurchaseGettersSetters
{
	public function getMerchantId()
	{
		return $this->getParameter('merchantId');
	}

	public function setMerchantId($value)
	{
		return $this->setParameter('merchantId', $value);
	}

	public function getMerchantUser()
	{
		return $this->getParameter('merchantUser');
	}

	public function setMerchantUser($value)
	{
		return $this->setParameter('merchantUser', $value);
	}

	public function getMerchantPassword()
	{
		return $this->getParameter('merchantPassword');
	}

	public function setMerchantPassword($value)
	{
		return $this->setParameter('merchantPassword', $value);
	}

	public function getClientIp()
	{
		return $this->getParameter('clientIp');
	}

	public function setClientIp($value)
	{
		return $this->setParameter('clientIp', $value);
	}

	public function getInstallment()
	{
		return $this->getParameter('installment');
	}

	public function setInstallment($value)
	{
		return $this->setParameter('installment', $value);
	}

	public function getSecure()
	{
		return $this->getParameter('secure');
	}

	public function setSecure($value)
	{
		return $this->setParameter('secure', $value);
	}

	public function getOrderNumber()
	{
		return $this->getParameter('orderNumber');
	}

	public function setOrderNumber($value)
	{
		return $this->setParameter('orderNumber', $value);
	}

	public function getVirtualPosOrderId()
	{
		return $this->getParameter('virtualPosOrderId');
	}

	public function setVirtualPosOrderId($value)
	{
		return $this->setParameter('virtualPosOrderId', $value);
	}

	public function getVoidRefundReason()
	{
		return $this->getParameter('voidRefundReason');
	}

	public function setVoidRefundReason($value)
	{
		return $this->setParameter('voidRefundReason', $value);
	}

	public function getBinNumber()
	{
		return $this->getParameter('binNumber');
	}

	public function setBinNumber($value)
	{
		return $this->setParameter('binNumber', $value);
	}

	public function getIsThreeD()
	{
		return $this->getParameter('isThreeD');
	}

	public function setIsThreeD($value)
	{
		return $this->setParameter('isThreeD', $value);
	}

	public function getIsPreAuth()
	{
		return $this->getParameter('isPreAuth');
	}

	public function setIsPreAuth($value)
	{
		return $this->setParameter('isPreAuth', $value);
	}

	public function getIsPoolPayment()
	{
		return $this->getParameter('isPoolPayment');
	}

	public function setIsPoolPayment($value)
	{
		return $this->setParameter('isPoolPayment', $value);
	}

	public function getSoftware()
	{
		return $this->getParameter('software');
	}

	public function setSoftware($value)
	{
		return $this->setParameter('software', $value);
	}

	public function getResultCode()
	{
		return $this->getParameter('resultCode');
	}

	public function setResultCode($value)
	{
		return $this->setParameter('resultCode', $value);
	}

	public function getResultMessage()
	{
		return $this->getParameter('resultMessage');
	}

	public function setResultMessage($value)
	{
		return $this->setParameter('resultMessage', $value);
	}

	public function getTrxCode()
	{
		return $this->getParameter('trxCode');
	}

	public function setTrxCode($value)
	{
		return $this->setParameter('trxCode', $value);
	}

	public function getOtherTrxCode()
	{
		return $this->getParameter('otherTrxCode');
	}

	public function setOtherTrxCode($value)
	{
		return $this->setParameter('otherTrxCode', $value);
	}

	public function getEndpoint()
	{
		return $this->endpoint;
	}
}
