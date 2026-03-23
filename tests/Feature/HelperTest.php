<?php

namespace Omnipay\Moka\Tests\Feature;

use Omnipay\Moka\Helpers\Helper;
use Omnipay\Moka\Tests\TestCase;

class HelperTest extends TestCase
{
    public function test_generate_check_key()
    {
        $checkKey = Helper::generateCheckKey('TestDealer', 'TestUser', 'TestPass');

        $expected = hash('sha256', 'TestDealerMKTestUserPDTestPass');

        self::assertEquals($expected, $checkKey);
    }

    public function test_generate_check_key_with_real_values()
    {
        $checkKey = Helper::generateCheckKey('1234', 'user@moka.com', 'p@ssword');

        $expected = hash('sha256', '1234MKuser@moka.comPDp@ssword');

        self::assertEquals($expected, $checkKey);
    }

    public function test_format_amount()
    {
        self::assertEquals(1.50, Helper::formatAmount('1.50'));
        self::assertEquals(100.00, Helper::formatAmount('100'));
        self::assertEquals(99.99, Helper::formatAmount('99.99'));
        self::assertEquals(0.01, Helper::formatAmount('0.01'));
    }

    public function test_format_expiry_month()
    {
        self::assertEquals('01', Helper::formatExpiryMonth('1'));
        self::assertEquals('12', Helper::formatExpiryMonth('12'));
        self::assertEquals('06', Helper::formatExpiryMonth('6'));
    }

    public function test_format_expiry_year()
    {
        self::assertEquals('2030', Helper::formatExpiryYear('30'));
        self::assertEquals('2030', Helper::formatExpiryYear('2030'));
        self::assertEquals('2099', Helper::formatExpiryYear('99'));
    }

    public function test_format_bin_number()
    {
        self::assertEquals('526911', Helper::formatBinNumber('5269111122223332'));
        self::assertEquals('411111', Helper::formatBinNumber('4111111111111111'));
    }
}
