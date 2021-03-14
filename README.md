# Interkassa PHP-SDK v1

<p align="center">
  <img width="230" height="230" src="https://avatars.githubusercontent.com/u/78364998?s=460&u=e3d910b7f2ef944383c79c4acb0e04f328285dab&v=4">
</p>

## Payment service provider
A payment service provider (PSP) offers shops online services for accepting electronic payments by a variety of payment methods including credit card, bank-based payments such as direct debit, bank transfer, and real-time bank transfer based on online banking. Typically, they use a software as a service model and form a single payment gateway for their clients (merchants) to multiple payment methods.
[read more](https://en.wikipedia.org/wiki/Payment_service_provider)

## Installation

This SDK uses composer.

Composer is a tool for dependency management in PHP. It allows you to declare the libraries your project depends on and it will manage (install/update) them for you.

For more information on how to use/install composer, please visit https://github.com/composer/composer

#### Composer installation
```cmd
composer require interkassa/php-sdk
```
#### Manual installation
```cmd
git clone https://github.com/interkassa/php-sdk.git
```

```php
<?php
require '/path-to-sdk/autoload.php';
```

## Direct POST request from your site without PHP coding.
```html
<form name="payment" method="post" action="https://sci.interkassa.com/" accept-charset="UTF-8">
  <input type="hidden" name="ik_co_id" value="51237daa8f2a2d8413000000"/>
  <input type="hidden" name="ik_pm_no" value="ID_1234"/>
  <input type="hidden" name="ik_am" value="1.44"/>
  <input type="hidden" name="ik_cur" value="uah"/>
  <input type="hidden" name="ik_desc" value="Payment Description"/>
  <input type="submit" value="Pay">
</form>
```

## Start configuration

```php
require 'vendor/autoload.php';

$configuration = new \Interkassa\Helper\Config();
$configuration->setCheckoutSecretKey('5rkFvckBLKcDHQrW');
$configuration->setAuthorizationKey('TpIJabcdefgdtNabcdefgMCeYvdVkF');
$configuration->setAccountId('ffa001aaaa00000000001234');

$SDKClient = new \Interkassa\Interkassa($configuration);
```
## Get link for rediret to SCI (Making invoice) [read more](https://docs.interkassa.com/#section/3.-Protokol)
```php
$invoiceRequest = new \Interkassa\Request\GetInvoiceRequest();
$invoiceRequest
    ->setCheckoutId('5fa005a06a00000000001234')
    ->setPaymentNumber('ID_1234')
    ->setAmount('100')
    ->setCurrency('UAH')
    ->setDescription('Payment Description');

$url = $SDKClient->makeInvoiceSciLink($invoiceRequest);
$SDKClient->redirect($url);
```

## Get form for redirecting to Pay System with parameters (Making invoice) [read more](https://docs.interkassa.com/#section/4.-Rasshirennye-vozmozhnosti/4.1.4.-Poluchenie-formy-platezha-platezhnogo-shlyuza)

```php
$invoiceRequest = new \Interkassa\Request\PostInvoiceRequest();
$invoiceRequest
    ->setCheckoutId('5fa005a06a00000000001234')
    ->setPaymentNumber('ID_1234')
    ->setAmount('100')
    ->setCurrency('UAH')
    ->setDescription('Payment Description')
    ->setAction('process')
    ->setPaywayVia('test_interkassa_test_xts');

$response = $SDKClient->makeInvoicePaySystemLink($invoiceRequest);

$code = $result->getCode();
$status = $result->getStatus();
$message = $result->getMessage();
$data = $result->getData();

$html = $SDKClient->redirectForm($data);
echo $html;
```
## Calculate invoice [read more](https://docs.interkassa.com/#section/4.-Rasshirennye-vozmozhnosti/4.1.3.-Poluchenie-dannyh-o-stoimosti-platezha-na-platezhnom-shlyuze)

```php
$invoiceRequest = new \Interkassa\Request\CalculateRequest();
$invoiceRequest
    ->setCheckoutId('5fa005a06a00000000001234')
    ->setPaymentNumber('ID_1234')
    ->setAmount('100')
    ->setCurrency('UAH')
    ->setDescription('Payment Description')
    ->setAction('payway')
    ->setPaywayVia('test_interkassa_test_xts');

$result = $SDKClient->calculateInvoice($invoiceRequest);

$code = $result->getCode();
$status = $result->getStatus();
$message = $result->getMessage();
$data = $result->getData();
```

## Obtaining a list of payment directions available for the checkout. [read more](https://docs.interkassa.com/#section/4.-Rasshirennye-vozmozhnosti/4.1.2.-Poluchenie-dostupnogo-dlya-kassy-spiska-platezhnyh-napravlenij)

```php
$invoiceRequest = new \Interkassa\Request\PaymentDirectionsRequest();
$invoiceRequest
    ->setCheckoutId('5fa005a06a00000000001234')
    ->setPaymentNumber('ID_1234')
    ->setAmount('100')
    ->setCurrency('UAH')
    ->setDescription('Payment Description')
    ->setAction('payways');

$result = $SDKClient->getPaymentDirection($invoiceRequest);

$code = $result->getCode();
$status = $result->getStatus();
$message = $result->getMessage();
$data = $result->getData();
```

## Returns all currency [read more](https://docs.interkassa.com/#operation/getCurrencyList)

```php
$result = $SDKClient->getCurrencyList();

$code = $result->getCode();
$status = $result->getStatus();
$data = $result->getData();
```

## Returns concrete currency by ID [read more](https://docs.interkassa.com/#operation/getCurrencyId)

```php
$result = $SDKClient->getCurrencyById('30');

$code = $result->getCode();
$status = $result->getStatus();
$data = $result->getData();
```

## Returns list of payment directions for input included in the Interkassa system.  [read more](https://docs.interkassa.com/#operation/getPaysystemInputPaywayList)

```php
$result = $SDKClient->getPaysystemInputPaywayList();

$code = $result->getCode();
$status = $result->getStatus();
$data = $result->getData();
```

##  Returns a payment direction for input by a specified ID included in the Interkassa system. [read more](https://docs.interkassa.com/#operation/getPaysystemInputPaywayId)

```php
$result = $SDKClient->getPaysystemInputPaywayById('11a001111100000000001234');

$code = $result->getCode();
$status = $result->getStatus();
$data = $result->getData();
```

## Returns list of payment directions for withdrawal included in the Interkassa system. [read more](https://docs.interkassa.com/#operation/getOutputPaywayList)

```php
$result = $SDKClient->getPaysystemOutputPaywayList();

$code = $result->getCode();
$status = $result->getStatus();
$data = $result->getData();
```

## Returns a payment direction for withdrawal, included in the Interkassa system. [read more](https://docs.interkassa.com/#operation/getOutputPaywayId)

```php
$result = $SDKClient->getPaysystemOutputPaywayById('11a001111100000000004321');

$code = $result->getCode();
$status = $result->getStatus();
$data = $result->getData();
```
## Returns list of accounts available to the user [read more](https://docs.interkassa.com/#operation/getAccountList)

```php
$result = $SDKClient->getAccountList();

$code = $result->getCode();
$status = $result->getStatus();
$data = $result->getData();
```

## Returns account data for a given ID [read more](https://docs.interkassa.com/#operation/getAccountId)

```php
$result = $SDKClient->getAccountById('ffa001aaaa00000000001234');

$code = $result->getCode();
$status = $result->getStatus();
$data = $result->getData();
```

## Returns list of checkouts linked to your account. [read more](https://docs.interkassa.com/#operation/get%D1%81heckoutList)

```php
$result = $SDKClient->getCheckoutList();

$code = $result->getCode();
$status = $result->getStatus();
$data = $result->getData();
```

## Returns checkout data for a given ID. [read more](https://docs.interkassa.com/#operation/get%D1%81heckoutId)

```php
$result = $SDKClient->getCheckoutById('11a002222200000000004321');

$code = $result->getCode();
$status = $result->getStatus();
$data = $result->getData();
```

## Returns all payments. [read more](https://docs.interkassa.com/#operation/getCoInvoice)

```php
$result = $SDKClient->getAllInvoices();

$code = $result->getCode();
$status = $result->getStatus();
$data = $result->getData();
```

## Returns data of payment by ID. [read more](https://docs.interkassa.com/#operation/getCoInvoiceId)

```php
$result = $SDKClient->getInvoiceById('134001234');

$code = $result->getCode();
$status = $result->getStatus();
$data = $result->getData();
```

## Returns a list of made payment withdrawals. [read more](https://docs.interkassa.com/#operation/getWithdrawList)

```php
$result = $SDKClient->getWithdrawList();

$code = $result->getCode();
$status = $result->getStatus();
$data = $result->getData();
```

## Returns information on a specific payment withdrawal ID. [read more](https://docs.interkassa.com/#operation/getWithdrawId)

```php
$result = $SDKClient->getWithdrawById('15001234');

$code = $result->getCode();
$status = $result->getStatus();
$data = $result->getData();
```

## Returns list of purses associated with an account, with their parameters. [read more](https://docs.interkassa.com/#operation/getPurseList)

```php
$result = $SDKClient->getPurseList();

$code = $result->getCode();
$status = $result->getStatus();
$data = $result->getData();

$result = $SDKClient->getPurseList([
   'checkoutId' => '11a002222200000000004321',
   'currency' => '20'
]);

$code = $result->getCode();
$status = $result->getStatus();
$data = $result->getData();
```

## Returns purse data for a given ID. [read more](https://docs.interkassa.com/#operation/getPurseId)

```php
$result = $SDKClient->getPurseById('404300001234');

$code = $result->getCode();
$status = $result->getStatus();
$data = $result->getData();
```

## Creates a refund in the Interkassa system. [read more](https://docs.interkassa.com/#operation/getRefundPost)

```php
$refundRequest = new \Interkassa\Request\RefundRequest();
$refundRequest
    ->setId('134001234')
    ->setAmount('15')
    ->setDescription('Reason of the refund');
$result = $SDKClient->makeRefund($refundRequest);

$code = $result->getCode();
$status = $result->getStatus();
$data = $result->getData();
```

## Creates a new withdraw in the Interkassa system. [read more](https://docs.interkassa.com/#operation/getWithdrawPost)

```php
$withdrawRequest = new \Interkassa\Request\WithdrawRequest();
$withdrawRequest
    ->setAmount('15')
    ->setMethod('card')
    ->setCurrency('uah')
    ->setAction('process')
    ->setDetail('card', '5100123412341234')
    ->setPurseId('300000912345')
    ->setUseShortAlias('true');
$result = $SDKClient->makeWithdraw($withdrawRequest);

$code = $result->getCode();
$status = $result->getStatus();
$data = $result->getData();
```

# Api
See [api-docs](https://docs.interkassa.com/)
