<?php

namespace SalesforceBulkApi\services;

use SalesforceBulkApi\api\UserApiSF;
use SalesforceBulkApi\dto\LoginResponseDto;
use GuzzleHttp\Client;
use SalesforceBulkApi\conf\LoginParams;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class ApiSalesforce
{
    /**
     * @var LoginParams
     */
    private $loginParams;

    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @var Response
     */
    private $lastResponse;

    /**
     * @var array
     */
    private $errors = [];

    /**
     * @var LoginResponseDto
     */
    private $session;

    /**
     * @param LoginParams $loginParams
     * @param array       $guzzleHttpClientConfig
     */
    public function __construct(LoginParams $loginParams, array $guzzleHttpClientConfig = ['timeout' => 3])
    {
        $this->loginParams = $loginParams;
        $this->httpClient  = new Client($guzzleHttpClientConfig);
        $this->session     = UserApiSF::login($this);
    }

    /**
     * @param Request $request
     * @param array   $options
     *
     * @return Response
     * @throws \Exception
     */
    public function send(Request $request, array $options = [])
    {
        try {
            $this->lastResponse = $this->httpClient->send($request, $options);
        } catch (\Exception $e) {
            $requestInfo    = [
                'uri'     => $request->getUri(),
                'method'  => $request->getMethod(),
                'headers' => $request->getHeaders(),
                'body'    => (string)$request->getBody()
            ];
            $errorMsg       =
                'ApiSalesforce request error: ' . $e->getCode() . ' ; ' . $e->getMessage() . ' ; Request info: '
                . json_encode($requestInfo);
            $this->errors[] = $errorMsg;

            throw new \Exception($errorMsg);
        }

        return clone $this->lastResponse;
    }

    /**
     * @return LoginParams
     */
    public function getLoginParams()
    {
        return $this->loginParams;
    }

    /**
     * @return Response
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param string $msg
     */
    public function addError($msg)
    {
        $this->errors[] = (string)$msg;
    }

    /**
     * @return LoginResponseDto
     */
    public function getSession()
    {
        return $this->session;
    }
}