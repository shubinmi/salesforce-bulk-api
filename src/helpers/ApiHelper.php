<?php

namespace SalesforceBulkApi\helpers;

use SalesforceBulkApi\exceptions\ApiRequestException;
use SalesforceBulkApi\exceptions\ApiResponseException;
use SalesforceBulkApi\exceptions\HttpClientException;
use SalesforceBulkApi\services\ApiSalesforce;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class ApiHelper
{
    /**
     * @param Request       $request
     * @param ApiSalesforce $service
     *
     * @return Response
     * @throws ApiRequestException
     * @throws ApiResponseException
     * @throws HttpClientException
     */
    public static function getResponse(Request $request, ApiSalesforce $service)
    {
        try {
            $response = $service->send($request, [ 'timeout' => 0 ]);
        } catch (\Exception $e) {
            throw new HttpClientException($e->getMessage());
        }
        if ($response->getStatusCode() == 500) {
            $error = 'API error: ' . $response->getBody()->getContents();
            $service->addError($error);
            throw new ApiRequestException($error);
        }
        if ($response->getStatusCode() >= 300) {
            $error =
                'API error: Status = ' . $response->getStatusCode() . ' ; ReasonPhrase = '
                . $response->getReasonPhrase() . ' ; Body = ' . $response->getBody()->getContents();
            $service->addError($error);
            throw new ApiResponseException($error);
        }

        return $response;
    }
}