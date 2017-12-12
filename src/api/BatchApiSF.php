<?php

namespace SalesforceBulkApi\api;

use SalesforceBulkApi\dto\BatchInfoDto;
use SalesforceBulkApi\dto\JobInfoDto;
use SalesforceBulkApi\dto\ResultAtBatchDto;
use SalesforceBulkApi\exceptions\ApiRequestException;
use SalesforceBulkApi\exceptions\ApiResponseException;
use SalesforceBulkApi\exceptions\HttpClientException;
use SalesforceBulkApi\exceptions\SFClientException;
use SalesforceBulkApi\helpers\ApiHelper;
use SalesforceBulkApi\services\ApiSalesforce;
use GuzzleHttp\Psr7\Request;

class BatchApiSF
{
    /**
     * @var string
     */
    public static $endpoint = 'https://%s.salesforce.com/services/async/%s/job/%s/batch';

    /**
     * @param ApiSalesforce $api
     * @param JobInfoDto    $job
     * @param string (json) $data
     *
     * @return BatchInfoDto
     * @throws \Exception
     */
    public static function addToJob(ApiSalesforce $api, JobInfoDto $job, $data)
    {
        $version  = $api->getLoginParams()->getApiVersion();
        $instance = $api->getSession()->getInstance();
        $request  = new Request(
            'POST',
            sprintf(self::$endpoint, $instance, $version, $job->getId()),
            [
                'Content-Type'   => 'application/json; charset=UTF8',
                'X-SFDC-Session' => $api->getSession()->getSessionId()
            ],
            $data
        );
        $response = ApiHelper::getResponse($request, $api);

        return new BatchInfoDto($response->getBody()->getContents());
    }

    /**
     * @param ApiSalesforce $api
     * @param BatchInfoDto  $batch
     *
     * @return BatchInfoDto
     * @throws \Exception
     */
    public static function info(ApiSalesforce $api, BatchInfoDto $batch)
    {
        $version  = $api->getLoginParams()->getApiVersion();
        $instance = $api->getSession()->getInstance();
        $request  = new Request(
            'GET',
            sprintf(self::$endpoint, $instance, $version, $batch->getJobId()) . '/'
            . $batch->getId(),
            [
                'Content-Type'   => 'application/json; charset=UTF8',
                'X-SFDC-Session' => $api->getSession()->getSessionId()
            ]
        );
        $response = ApiHelper::getResponse($request, $api);

        return new BatchInfoDto($response->getBody()->getContents());
    }

    /**
     * @param ApiSalesforce $api
     * @param JobInfoDto    $job
     *
     * @return BatchInfoDto[]
     * @throws SFClientException
     * @throws \Exception
     */
    public static function infoForAllInJob(ApiSalesforce $api, JobInfoDto $job)
    {
        $version  = $api->getLoginParams()->getApiVersion();
        $instance = $api->getSession()->getInstance();
        $request  = new Request(
            'GET',
            sprintf(self::$endpoint, $instance, $version, $job->getId()),
            [
                'Content-Type'   => 'application/json; charset=UTF8',
                'X-SFDC-Session' => $api->getSession()->getSessionId()
            ]
        );
        $response = ApiHelper::getResponse($request, $api);
        try {
            $data   = json_decode($response->getBody()->getContents(), true);
            $result = [];
            foreach ($data['batchInfo'] as $item) {
                $result[$item['id']] = new BatchInfoDto($item);
            }
        } catch (\Exception $e) {
            $msg = 'SF batch api at parse of response error: ' . $e->getMessage()
                . ' ; Response = ' . $response->getBody()->getContents();
            $api->addError($msg);
            throw new SFClientException($msg);
        }

        return $result;
    }

    /**
     * @param ApiSalesforce $api
     * @param BatchInfoDto  $batch
     *
     * @return ResultAtBatchDto[]
     * @throws SFClientException
     * @throws ApiRequestException
     * @throws ApiResponseException
     * @throws HttpClientException
     */
    public static function results(ApiSalesforce $api, BatchInfoDto $batch)
    {
        $version  = $api->getLoginParams()->getApiVersion();
        $instance = $api->getSession()->getInstance();
        $request  = new Request(
            'GET',
            sprintf(self::$endpoint, $instance, $version, $batch->getJobId())
            . '/' . $batch->getId() . '/result',
            [
                'Content-Type'   => 'application/json; charset=UTF8',
                'X-SFDC-Session' => $api->getSession()->getSessionId()
            ]
        );
        $response = ApiHelper::getResponse($request, $api);
        try {
            $data   = json_decode($response->getBody()->getContents(), true);
            $result = [];
            foreach ($data as $item) {
                $result[] = new ResultAtBatchDto($item);
            }
        } catch (\Exception $e) {
            $msg = 'SF batch api at parse of response error: ' . $e->getMessage()
                . ' ; Response = ' . $response->getBody()->getContents();
            $api->addError($msg);
            throw new SFClientException($msg);
        }

        return $result;
    }

    /**
     * @param ApiSalesforce $api
     * @param BatchInfoDto  $batch
     * @param string        $batchResultId
     *
     * @return array
     * @throws SFClientException
     * @throws \Exception
     */
    public static function result(ApiSalesforce $api, BatchInfoDto $batch, $batchResultId)
    {
        $version  = $api->getLoginParams()->getApiVersion();
        $instance = $api->getSession()->getInstance();
        $request  = new Request(
            'GET',
            sprintf(self::$endpoint, $instance, $version, $batch->getJobId())
            . '/' . $batch->getId() . '/result/' . $batchResultId,
            [
                'Content-Type'   => 'application/json; charset=UTF8',
                'X-SFDC-Session' => $api->getSession()->getSessionId()
            ]
        );
        $response = ApiHelper::getResponse($request, $api);
        try {
            $data   = json_decode($response->getBody()->getContents(), true);
            $result = [];
            foreach ($data as $item) {
                $result[] = $item;
            }
        } catch (\Exception $e) {
            $msg = 'SF batch api at parse of response error: ' . $e->getMessage()
                . ' ; Response = ' . $response->getBody()->getContents();
            $api->addError($msg);
            throw new SFClientException($msg);
        }

        return $result;
    }
}