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

# Api
See [api-docs](https://docs.interkassa.com/)
