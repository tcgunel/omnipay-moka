# Omnipay: Moka

**Moka gateway for the Omnipay PHP payment processing library**

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP. This package implements Moka support for Omnipay.

## Installation

```bash
composer require tcgunel/omnipay-moka
```

## Usage

### Gateway Setup

```php
use Omnipay\Omnipay;

$gateway = Omnipay::create('Moka');
$gateway->setMerchantId('YOUR_DEALER_CODE');
$gateway->setMerchantUser('YOUR_USERNAME');
$gateway->setMerchantPassword('YOUR_PASSWORD');
$gateway->setTestMode(true); // uses https://service.refmoka.com
```

### Non-3D Purchase

```php
$response = $gateway->purchase([
    'amount'        => '1.50',
    'currency'      => 'TRY',
    'transactionId' => 'ORDER-12345',
    'installment'   => 1,
    'clientIp'      => '127.0.0.1',
    'card'          => [
        'firstName'   => 'John',
        'lastName'    => 'Doe',
        'number'      => '5269111122223332',
        'expiryMonth' => '01',
        'expiryYear'  => '2030',
        'cvv'         => '123',
    ],
])->send();

if ($response->isSuccessful()) {
    $transactionReference = $response->getTransactionReference(); // VirtualPosOrderId
}
```

### 3D Secure Purchase

```php
$response = $gateway->purchase([
    'amount'        => '1.50',
    'currency'      => 'TRY',
    'transactionId' => 'ORDER-12345',
    'installment'   => 1,
    'clientIp'      => '127.0.0.1',
    'secure'        => true,
    'returnUrl'     => 'https://yoursite.com/callback',
    'card'          => [
        'firstName'   => 'John',
        'lastName'    => 'Doe',
        'number'      => '5269111122223332',
        'expiryMonth' => '01',
        'expiryYear'  => '2030',
        'cvv'         => '123',
    ],
])->send();

if ($response->isRedirect()) {
    $response->redirect(); // redirects to Moka 3D page
}
```

### Complete Purchase (3D callback)

```php
$response = $gateway->completePurchase([
    'otherTrxCode'  => $_POST['OtherTrxCode'],  // orderNumber
    'trxCode'       => $_POST['trxCode'],        // transactionId
    'resultCode'    => $_POST['resultCode'] ?? null,
    'resultMessage' => $_POST['resultMessage'] ?? null,
])->send();

if ($response->isSuccessful()) {
    // Payment verified
}
```

### Cancel (Void)

```php
$response = $gateway->void([
    'virtualPosOrderId' => 'MOKA_ORDER_ID',
    'transactionId'     => 'ORDER-12345',
    'clientIp'          => '127.0.0.1',
])->send();

if ($response->isSuccessful()) {
    // Void successful
}
```

### Refund

```php
$response = $gateway->refund([
    'virtualPosOrderId' => 'MOKA_ORDER_ID',
    'transactionId'     => 'ORDER-12345',
    'amount'            => '9.99',
])->send();

if ($response->isSuccessful()) {
    // Refund successful
}
```

### BIN Lookup

```php
$response = $gateway->binLookup([
    'binNumber' => '526911',
])->send();

if ($response->isSuccessful()) {
    $creditType           = $response->getCreditType();           // e.g. "CreditCard"
    $maxInstallmentNumber = $response->getMaxInstallmentNumber(); // e.g. 12
}
```

### Installment Query

```php
$response = $gateway->installmentQuery([
    'binNumber'   => '526911',
    'amount'      => '100.00',
    'currency'    => 'TRY',
    'installment' => 3,
    'isThreeD'    => 0,
])->send();

if ($response->isSuccessful()) {
    $paymentAmount = $response->getPaymentAmount(); // total with commission
}
```

## Endpoints

| Environment | URL |
|---|---|
| Test | https://service.refmoka.com |
| Live | https://service.moka.com |

## Authentication

Moka uses CheckKey-based authentication:

```
CheckKey = SHA256(DealerCode + "MK" + Username + "PD" + Password)
```

This is generated automatically by the gateway.

## Currency

Omnipay uses ISO currency codes (TRY, USD, EUR, GBP). The gateway automatically maps `TRY` to Moka's `TL` format.

## Testing

```bash
composer test
```

## License

MIT
