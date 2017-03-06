<?php

namespace SalesforceBulkApi\api;

use SalesforceBulkApi\dto\BatchInfoDto;
use SalesforceBulkApi\dto\JobInfoDto;
use SalesforceBulkApi\dto\ResultAtBatchDto;
use SalesforceBulkApi\exceptions\SFClientException;
use SalesforceBulkApi\helpers\ApiHelper;
use SalesforceBulkApi\services\ApiSalesforce;
use GuzzleHttp\Psr7\Request;

class BatchApiSF
{
    /**
     * @var string
     */
    public static $endpoint = 'https://%s.salesforce.com/services/async/39.0/job/%s/batch';

    /**
     * @param ApiSalesforce $api
     * @param JobInfoDto    $job
     * @param string (json) $data
     *
     * @return BatchInfoDto
     */
    public static function addToJob(ApiSalesforce $api, JobInfoDto $job, $data)
    {
        $request  = new Request(
            'POST',
            sprintf(self::$endpoint, $api->getSession()->getInstance(), $job->getId()),
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
     */
    public static function info(ApiSalesforce $api, BatchInfoDto $batch)
    {
        $request  = new Request(
            'GET',
            sprintf(self::$endpoint, $api->getSession()->getInstance(), $batch->getJobId()) . '/' . $batch->getId(),
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
     */
    public static function infoForAllInJob(ApiSalesforce $api, JobInfoDto $job)
    {
        $request  = new Request(
            'GET',
            sprintf(self::$endpoint, $api->getSession()->getInstance(), $job->getId()),
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
                $result[] = new BatchInfoDto($item);
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
     */
    public static function results(ApiSalesforce $api, BatchInfoDto $batch)
    {
        $request  = new Request(
            'GET',
            sprintf(self::$endpoint, $api->getSession()->getInstance(), $batch->getJobId()) . '/' . $batch->getId()
            . '/result',
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
}