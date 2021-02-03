<?php

namespace Interkassa;

use Interkassa\Exception\BadRequestException;
use Interkassa\Exception\ValidationFieldException;
use Interkassa\Helper\Config;
use Interkassa\Helper\Signature;
use Interkassa\Helper\Validator;
use Interkassa\HttpClient\ClientInterface;
use Interkassa\HttpClient\HttpCurl;
use Interkassa\Request\BaseInvoiceRequest;
use Interkassa\Request\CalculateRequest;
use Interkassa\Request\GetInvoiceRequest;
use Interkassa\Request\PaymentDirectionsRequest;
use Interkassa\Request\PostInvoiceRequest;
use Interkassa\Response\InterkassaResponse;
use Interkassa\Response\ResponseDirector;
use Interkassa\Response\SciResponseBuilder;

class Interkassa
{
    /**
     * @var Config
     */
    private $apiConfig;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var ResponseDirector
     */
    private $director;

    /**
     * @var Signature
     */
    private $signatureHelper;

    /**
     * Interkassa constructor.
     *
     * @param Config $configuration
     */
    public function __construct(Config $configuration)
    {
        $this->apiConfig = $configuration;

        $this->client = new HttpCurl();
        $this->validator = new Validator();
        $this->director = new ResponseDirector();
        $this->signatureHelper = new Signature();
    }

    /**
     * Returns payment link for redirect to Interkassa SCI.
     *
     * @link https://docs.interkassa.com/#section/3.-Protokol
     *
     * @param GetInvoiceRequest $request
     *
     * @throws BadRequestException
     */
    public function makeInvoiceSciLink(GetInvoiceRequest $request): string
    {
        $this->validator->validateRequiredFields($this->makeSignature($request));

        return $this->apiConfig->getSciUrl() . '?' . http_build_query($request->getData());
    }

    /**
     * Returns payment link to payment gateway.
     *
     * @link https://docs.interkassa.com/#section/4.-Rasshirennye-vozmozhnosti/4.1.4.-Poluchenie-formy-platezha-platezhnogo-shlyuza
     *
     * @param PostInvoiceRequest $request
     *
     * @throws Exception\HttpClientException
     * @throws Exception\ValidationFieldException
     *
     * @return InterkassaResponse
     */
    public function makeInvoicePaySystemLink(PostInvoiceRequest $request): InterkassaResponse
    {
        return $this->makeSciRequest($request);
    }

    /**
     * Receiving data on the cost of payment on the payment gateway.
     *
     * @link https://docs.interkassa.com/#section/4.-Rasshirennye-vozmozhnosti/4.1.3.-Poluchenie-dannyh-o-stoimosti-platezha-na-platezhnom-shlyuze
     *
     * @param CalculateRequest $request
     *
     * @throws Exception\AccessDeniedHttpException
     * @throws Exception\HttpClientException
     * @throws Exception\ValidationFieldException
     * @throws BadRequestException
     *
     * @return InterkassaResponse
     */
    public function calculateInvoice(CalculateRequest $request): InterkassaResponse
    {
        return $this->makeSciRequest($request);
    }

    /**
     * Obtaining a list of payment directions available for the checkout.
     *
     * @link https://docs.interkassa.com/#section/4.-Rasshirennye-vozmozhnosti/4.1.2.-Poluchenie-dostupnogo-dlya-kassy-spiska-platezhnyh-napravlenij
     *
     * @param PaymentDirectionsRequest $request
     *
     * @throws Exception\HttpClientException
     * @throws Exception\ValidationFieldException
     *
     * @return InterkassaResponse
     */
    public function getPaymentDirection(PaymentDirectionsRequest $request): InterkassaResponse
    {
        return $this->makeSciRequest($request);
    }

    /**
     * @param string $urlForRedirect
     */
    public function redirect(string $urlForRedirect)
    {
        header('Location:' . $urlForRedirect . "\r\n");
    }

    /**
     * @param BaseInvoiceRequest $request
     *
     * @throws Exception\HttpClientException
     * @throws Exception\ValidationFieldException
     *
     * @return InterkassaResponse
     */
    private function makeSciRequest(BaseInvoiceRequest $request): InterkassaResponse
    {
        $this->validator->validateRequiredFields($this->makeSignature($request));

        $response = $this->post($this->apiConfig->getSciUrl(), [], $request->getData());

        return $this->director->build(new SciResponseBuilder(), $response);
    }

    /**
     * @param BaseInvoiceRequest $request
     *
     * @return BaseInvoiceRequest
     */
    private function makeSignature(BaseInvoiceRequest $request): BaseInvoiceRequest
    {
        if ($request->isNeedSignature() && $this->apiConfig->getCheckoutSecretKey() !== '') {
            $signature = $this->signatureHelper->makeSignature(
                $request->getData(),
                $this->apiConfig->getCheckoutSecretKey(),
                $this->apiConfig->getAlgorithm()
            );

            $request->addSignatureToData($signature);

            return $request;
        }

        $request->withSignature(false);

        return $request;
    }

    /**
     * @param string $url
     * @param array  $headers
     * @param array  $data
     *
     * @throws Exception\HttpClientException
     *
     * @return HttpClient\HttpClientResponse
     */
    private function get(string $url, array $headers = [], array $data = [])
    {
        $requestParams = count($data) == 0 ? '' : '?' . http_build_query($data);

        return $this->client->request('GET', $url . $requestParams, $headers, $data);
    }

    /**
     * @param string $url
     * @param array  $headers
     * @param array  $data
     *
     * @throws Exception\HttpClientException
     *
     * @return HttpClient\HttpClientResponse
     */
    private function post(string $url, array $headers = [], array $data = [])
    {
        return $this->client->request('POST', $url, $headers, $data);
    }

    /**
     * @param array $data
     *
     * @return string
     */
    public function redirectForm(array $data)
    {
        if (!isset($data['paymentForm'])) {
            throw new ValidationFieldException('Field paymentForm not found.');
        }
        $method = $data['paymentForm']['method'];
        $url = $data['paymentForm']['action'];
        $hiddenInputs = '';

        foreach ($data['paymentForm']['parameters'] as $name => $value) {
            $hiddenInputs .= "<input type='hidden' name='$name' value='$value'/>";
        }

        return <<<FORM
            <script>
                document.addEventListener('DOMContentLoaded', function(){
                    document.getElementById('payFormInterkassa').submit();
                }); 
            </script>
            <form id='payFormInterkassa' method='$method' action='$url' accept-charset='UTF-8'>
                $hiddenInputs
                <input type='submit' value=''>
            </form>
FORM;
    }
}
