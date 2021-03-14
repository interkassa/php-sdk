<?php

namespace Interkassa;

use Interkassa\Exception\BadRequestException;
use Interkassa\Exception\ValidationFieldException;
use Interkassa\Helper\Config;
use Interkassa\Helper\Signature;
use Interkassa\Helper\Validator;
use Interkassa\HttpClient\ClientInterface;
use Interkassa\HttpClient\HttpClientResponse;
use Interkassa\HttpClient\HttpCurl;
use Interkassa\Request\BaseInvoiceRequest;
use Interkassa\Request\CalculateRequest;
use Interkassa\Request\GetInvoiceRequest;
use Interkassa\Request\PaymentDirectionsRequest;
use Interkassa\Request\PostInvoiceRequest;
use Interkassa\Request\RefundRequest;
use Interkassa\Request\WithdrawRequest;
use Interkassa\Response\ApiResponseBuilder;
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
     * Returns a list of currencies and rates used in the system.
     *
     * @link https://docs.interkassa.com/#operation/getCurrencyList
     *
     * @return InterkassaResponse
     */
    public function getCurrencyList(): InterkassaResponse
    {
        $response = $this->get($this->makeUrlForApi('/currency'));

        return $this->buildApiResponse($response);
    }

    /**
     * Returns a given currency and a list of rates.
     *
     * @link https://docs.interkassa.com/#operation/getCurrencyId
     *
     * @param string $currencyId
     *
     * @return InterkassaResponse
     */
    public function getCurrencyById(string $currencyId): InterkassaResponse
    {
        $response = $this->get($this->makeUrlForApi('/currency', $currencyId));

        return $this->buildApiResponse($response);
    }

    /**
     * Returns a list of payment directions for input included in the Interkassa system.
     *
     * @link https://docs.interkassa.com/#operation/getPaysystemInputPaywayList
     *
     * @return InterkassaResponse
     */
    public function getPaysystemInputPaywayList(): InterkassaResponse
    {
        $response = $this->get(
            $this->makeUrlForApi('/paysystem-input-payway'),
            $this->getAuthorizationHeader()
        );

        return $this->buildApiResponse($response);
    }

    /**
     * Returns a payment direction for input by a specified ID included in the Interkassa system.
     *
     * @link https://docs.interkassa.com/#operation/getPaysystemInputPaywayId
     *
     * @param string $paysystemInputPaywayId
     *
     * @return InterkassaResponse
     */
    public function getPaysystemInputPaywayById(string $paysystemInputPaywayId): InterkassaResponse
    {
        $response = $this->get(
            $this->makeUrlForApi('/paysystem-input-payway', $paysystemInputPaywayId),
            $this->getAuthorizationHeader()
        );

        return $this->buildApiResponse($response);
    }

    /**
     * Returns a list of payment directions for withdrawal included in the Interkassa system.
     *
     * @link https://docs.interkassa.com/#operation/getOutputPaywayList
     *
     * @return InterkassaResponse
     */
    public function getPaysystemOutputPaywayList(): InterkassaResponse
    {
        $response = $this->get(
            $this->makeUrlForApi('/paysystem-output-payway'),
            $this->getAuthorizationHeader()
        );

        return $this->buildApiResponse($response);
    }

    /**
     * Returns a payment direction for withdrawal, included in the Interkassa system.
     *
     * @link https://docs.interkassa.com/#operation/getOutputPaywayId
     *
     * @param string $paysystemOutputPaywayId
     *
     * @return InterkassaResponse
     */
    public function getPaysystemOutputPaywayById(string $paysystemOutputPaywayId): InterkassaResponse
    {
        $response = $this->get(
            $this->makeUrlForApi('/paysystem-output-payway', $paysystemOutputPaywayId),
            $this->getAuthorizationHeader()
        );

        return $this->buildApiResponse($response);
    }

    /**
     * Returns a list of accounts available to the user.
     *
     * @link https://docs.interkassa.com/#operation/getAccountList
     *
     * @return InterkassaResponse
     */
    public function getAccountList(): InterkassaResponse
    {
        $response = $this->get(
            $this->makeUrlForApi('/account'),
            $this->getAuthorizationHeader()
        );

        return $this->buildApiResponse($response);
    }

    /**
     * Returns account data for a given ID.
     *
     * @link https://docs.interkassa.com/#operation/getAccountId
     *
     * @param string $accountId
     *
     * @return InterkassaResponse
     */
    public function getAccountById(string $accountId): InterkassaResponse
    {
        $response = $this->get(
            $this->makeUrlForApi('/account', $accountId),
            $this->getAuthorizationHeader()
        );

        return $this->buildApiResponse($response);
    }

    /**
     * Returns a list of checkouts linked to your account.
     *
     * @link https://docs.interkassa.com/#operation/get%D1%81heckoutList
     *
     * @return InterkassaResponse
     */
    public function getCheckoutList(): InterkassaResponse
    {
        $response = $this->get(
            $this->makeUrlForApi('/checkout'),
            $this->getAuthorizationHeader()
        );

        return $this->buildApiResponse($response);
    }

    /**
     * Returns checkout data for a given ID.
     *
     * @link https://docs.interkassa.com/#operation/get%D1%81heckoutId
     *
     * @param string $checkoutId
     *
     * @return InterkassaResponse
     */
    public function getCheckoutById(string $checkoutId): InterkassaResponse
    {
        $response = $this->get(
            $this->makeUrlForApi('/checkout', $checkoutId),
            $this->getAuthorizationHeader()
        );

        return $this->buildApiResponse($response);
    }

    /**
     * Returns all payments.
     *
     * @link https://docs.interkassa.com/#operation/getCoInvoice
     *
     * @return InterkassaResponse
     */
    public function getAllInvoices(): InterkassaResponse
    {
        $response = $this->get(
            $this->makeUrlForApi('/co-invoice'),
            $this->getAuthorizationHeader()
        );

        return $this->buildApiResponse($response);
    }

    /**
     * Returns data of payment by ID.
     *
     * @link https://docs.interkassa.com/#operation/getCoInvoiceId
     *
     * @param string $invoiceId
     *
     * @return InterkassaResponse
     */
    public function getInvoiceById(string $invoiceId): InterkassaResponse
    {
        $response = $this->get(
            $this->makeUrlForApi('/co-invoice', $invoiceId),
            $this->getAuthorizationHeader()
        );

        return $this->buildApiResponse($response);
    }

    /**
     * Returns a list of made payment withdrawals.
     *
     * @link https://docs.interkassa.com/#operation/getWithdrawList
     *
     * @param array $params
     *
     * @return InterkassaResponse
     */
    public function getWithdrawList(array $params = []): InterkassaResponse
    {
        $response = $this->get(
            $this->makeUrlForApi('/withdraw'),
            $this->getAuthorizationHeader(),
            $params
        );

        return $this->buildApiResponse($response);
    }

    /**
     * Returns information on a specific payment withdrawal ID.
     *
     * @link https://docs.interkassa.com/#operation/getWithdrawId
     *
     * @param string $withdrawId
     *
     * @return InterkassaResponse
     */
    public function getWithdrawById(string $withdrawId): InterkassaResponse
    {
        $response = $this->get(
            $this->makeUrlForApi('/withdraw', $withdrawId),
            $this->getAuthorizationHeader()
        );

        return $this->buildApiResponse($response);
    }

    /**
     * Creates a new withdraw in the Interkassa system.
     *
     * @link https://docs.interkassa.com/#operation/getWithdrawPost
     *
     * @param WithdrawRequest $request
     *
     * @throws Exception\ValidationFieldException
     * @throws BadRequestException
     *
     * @return InterkassaResponse
     */
    public function makeWithdraw(WithdrawRequest $request): InterkassaResponse
    {
        $this->validator->validateRequiredFields($request);
        $this->validator->validateDetailForWithdraw($request);

        $response = $this->post(
            $this->makeUrlForApi('/withdraw'),
            $this->getAuthorizationHeader(),
            $request->getData()
        );

        return $this->buildApiResponse($response);
    }

    /**
     * Creates a refund in the Interkassa system.
     *
     * @link https://docs.interkassa.com/#operation/getRefundPost
     *
     * @param RefundRequest $request
     *
     * @throws Exception\ValidationFieldException
     * @throws BadRequestException
     *
     * @return InterkassaResponse
     */
    public function makeRefund(RefundRequest $request): InterkassaResponse
    {
        $this->validator->validateRequiredFields($request);

        $response = $this->post(
            $this->makeUrlForApi('/refund'),
            $this->getAuthorizationHeader(),
            $request->getData()
        );

        return $this->buildApiResponse($response);
    }

    /**
     * Returns a list of purses associated with an account, with their parameters.
     *
     * @link https://docs.interkassa.com/#operation/getPurseList
     *
     * @param array $params
     *
     * @return InterkassaResponse
     */
    public function getPurseList(array $params = []): InterkassaResponse
    {
        $response = $this->get(
            $this->makeUrlForApi('/purse'),
            $this->getAuthorizationHeader(),
            $params
        );

        return $this->buildApiResponse($response);
    }

    /**
     * Returns purse data for a given ID.
     *
     * @link https://docs.interkassa.com/#operation/getPurseId
     *
     * @param string $purseId
     *
     * @return InterkassaResponse
     */
    public function getPurseById(string $purseId): InterkassaResponse
    {
        $response = $this->get(
            $this->makeUrlForApi('/purse', $purseId),
            $this->getAuthorizationHeader()
        );

        return $this->buildApiResponse($response);
    }

    /**
     * Returns payment link for redirect to Interkassa SCI.
     *
     * @link https://docs.interkassa.com/#section/3.-Protokol
     *
     * @param GetInvoiceRequest $request
     *
     * @throws BadRequestException
     *
     * @return string
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
     * @param HttpClientResponse $response
     *
     * @return InterkassaResponse
     */
    private function buildApiResponse(HttpClientResponse $response): InterkassaResponse
    {
        return $this->director->build(new ApiResponseBuilder(), $response);
    }

    /**
     * @return array
     */
    private function getAuthorizationHeader(): array
    {
        return ['Authorization: Basic ' . base64_encode(
            sprintf(
                '%s:%s',
                $this->apiConfig->getAccountId(),
                $this->apiConfig->getAuthorizationKey()
            )
        )];
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
    private function get(string $url, array $headers = [], array $data = []): HttpClientResponse
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
    private function post(string $url, array $headers = [], array $data = []): HttpClientResponse
    {
        return $this->client->request('POST', $url, $headers, $data);
    }

    /**
     * @param string $path
     * @param string $entityId
     *
     * @return string
     */
    private function makeUrlForApi(string $path, string $entityId = ''): string
    {
        return $this->apiConfig->getApiUrl() . $this->getPath($path, $entityId);
    }

    /**
     * @param string $path
     * @param string $entityId
     *
     * @return string
     */
    private function getPath(string $path, string $entityId): string
    {
        return empty($entityId) ? $path : $path . '/' . $entityId;
    }

    /**
     * @param array $data
     *
     * @return string
     */
    public function redirectForm(array $data): string
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
