<?php

namespace SalesforceBulkApi\api;

use SalesforceBulkApi\conf\LoginParams;
use SalesforceBulkApi\dto\LoginResponseDto;
use SalesforceBulkApi\exceptions\ApiRequestException;
use SalesforceBulkApi\exceptions\ApiResponseException;
use SalesforceBulkApi\exceptions\HttpClientException;
use SalesforceBulkApi\exceptions\SFClientException;
use SalesforceBulkApi\services\ApiSalesforce;
use GuzzleHttp\Psr7\Request;

class UserApiSF
{
    /**
     * @var string
     */
    public static $endpoint = 'https://%s.salesforce.com/services/Soap/%s/%s';

    /**
     * @param ApiSalesforce $api
     *
     * @return LoginResponseDto
     * @throws \Exception
     */
    public static function login(ApiSalesforce $api)
    {
        $asWho          = $api->getLoginParams()->amIPartner() ? 'u' : 'c';
        $version        = $api->getLoginParams()->getApiVersion();
        $endpointPrefix = $api->getLoginParams()->getEndpointPrefix();

        $request = new Request(
            'POST',
            sprintf(self::$endpoint, $endpointPrefix, $asWho, $version),
            [
                'Content-Type' => 'text/xml; charset=UTF8',
                'SOAPAction'   => 'login'
            ],
            self::getXml($api->getLoginParams())
        );
        try {
            $response = $api->send($request, [ 'timeout' => 0 ]);
        } catch (\Exception $e) {
            throw new HttpClientException($e->getMessage());
        }

        if ($response->getStatusCode() != 200 && $response->getStatusCode() !== 500) {
            $error =
                'API error: Status = ' . $response->getStatusCode() . ' ; ReasonPhrase = '
                . $response->getReasonPhrase() . ' ; Body = ' . $response->getBody()->getContents();
            $api->addError($error);
            throw new ApiResponseException($error);
        }

        $dom = new \DOMDocument;
        $dom->loadXML($response->getBody());
        if ($response->getStatusCode() == 500) {
            $fail = $dom->getElementsByTagName('faultstring');
            if ($fail->length == 0) {
                throw new SFClientException(
                    'SF Api waiting behavior changed. Error: incorrect response = ' . $response->getBody()->getContents(
                    )
                );
            }
            $error = 'API error: ' . $fail[0]->nodeValue;
            $api->addError($error);
            throw new ApiRequestException($error);
        }

        return new LoginResponseDto($dom);
    }

    /**
     * @param LoginParams $params
     *
     * @return string
     */
    private static function getXml(LoginParams $params)
    {
        /** @noinspection XmlUnusedNamespaceDeclaration */
        $xml = <<<XML
<?xml version="1.0" encoding="utf-8" ?>
<env:Envelope xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:env="http://schemas.xmlsoap.org/soap/envelope/">
  <env:Body>
    <n1:login xmlns:n1="urn:partner.soap.sforce.com">
      <n1:username>%s</n1:username>
      <n1:password>%s</n1:password>
    </n1:login>
  </env:Body>
</env:Envelope>
XML;

        $login = $params->getUserName();
        $pass  = $params->getUserPass() . $params->getUserSecretToken();

        $login = htmlspecialchars($login, ENT_QUOTES, 'UTF-8');
        $pass = htmlspecialchars($pass, ENT_QUOTES, 'UTF-8');

        return sprintf($xml, $login, $pass);
    }
}
