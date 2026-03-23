<?php

namespace Omnipay\Moka\Helpers;

class Helper
{
	/**
	 * Generate Moka CheckKey: SHA256(DealerCode + "MK" + Username + "PD" + Password)
	 */
	public static function generateCheckKey(string $dealerCode, string $username, string $password): string
	{
		$hashString = $dealerCode . "MK" . $username . "PD" . $password;

		return hash('sha256', $hashString);
	}

	/**
	 * Format amount to Moka's expected float format (e.g. 1.50)
	 *
	 * @param string|float $amount
	 * @return float
	 */
	public static function formatAmount($amount): float
	{
		return round((float) $amount, 2);
	}

	/**
	 * Format expiry month to 2-digit string
	 */
	public static function formatExpiryMonth(string $month): string
	{
		return str_pad($month, 2, '0', STR_PAD_LEFT);
	}

	/**
	 * Format expiry year to 4-digit string
	 */
	public static function formatExpiryYear(string $year): string
	{
		if (strlen($year) === 2) {
			return '20' . $year;
		}

		return $year;
	}

	/**
	 * Format BIN number (first 6 digits)
	 */
	public static function formatBinNumber(string $cardNumber): string
	{
		return substr($cardNumber, 0, 6);
	}

	/**
	 * Convert ISO currency code to Moka currency code.
	 * Moka uses "TL" instead of the ISO standard "TRY".
	 */
	public static function mapCurrency(?string $currency): string
	{
		$map = [
			'TRY' => 'TL',
			'USD' => 'USD',
			'EUR' => 'EUR',
			'GBP' => 'GBP',
		];

		if ($currency === null) {
			return 'TL';
		}

		return $map[strtoupper($currency)] ?? $currency;
	}
}
