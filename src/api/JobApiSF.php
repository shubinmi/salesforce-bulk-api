<?php

namespace SalesforceBulkApi\api;

use SalesforceBulkApi\dto\CreateJobDto;
use SalesforceBulkApi\dto\JobInfoDto;
use SalesforceBulkApi\helpers\ApiHelper;
use SalesforceBulkApi\services\ApiSalesforce;
use GuzzleHttp\Psr7\Request;

class JobApiSF
{
    /**
     * @var string
     */
    public static $endpoint = 'https://%s.salesforce.com/services/async/39.0/job';

    /**
     * @param ApiSalesforce $api
     * @param CreateJobDto  $dto
     *
     * @return JobInfoDto
     */
    public static function create(ApiSalesforce $api, CreateJobDto $dto)
    {
        $request  = new Request(
            'POST',
            sprintf(self::$endpoint, $api->getSession()->getInstance()),
            [
                'Content-Type'   => 'application/json; charset=UTF8',
                'X-SFDC-Session' => $api->getSession()->getSessionId()
            ],
            $dto->toJson()
        );
        $response = ApiHelper::getResponse($request, $api);

        return new JobInfoDto($response->getBody()->getContents());
    }

    /**
     * @param ApiSalesforce $api
     * @param JobInfoDto    $job
     *
     * @return JobInfoDto
     */
    public static function detail(ApiSalesforce $api, JobInfoDto $job)
    {
        $request  = new Request(
            'GET',
            sprintf(self::$endpoint, $api->getSession()->getInstance()) . '/' . $job->getId(),
            [
                'Content-Type'   => 'application/json; charset=UTF8',
                'X-SFDC-Session' => $api->getSession()->getSessionId()
            ]
        );
        $response = ApiHelper::getResponse($request, $api);

        return new JobInfoDto($response->getBody()->getContents());
    }

    /**
     * @param ApiSalesforce $api
     * @param JobInfoDto    $job
     *
     * @return JobInfoDto
     */
    public static function close(ApiSalesforce $api, JobInfoDto $job)
    {
        $request  = new Request(
            'POST',
            sprintf(self::$endpoint, $api->getSession()->getInstance()) . '/' . $job->getId(),
            [
                'Content-Type'   => 'application/json; charset=UTF8',
                'X-SFDC-Session' => $api->getSession()->getSessionId()
            ],
            '{ "state" : "Closed" }'
        );
        $response = ApiHelper::getResponse($request, $api);

        return new JobInfoDto($response->getBody()->getContents());
    }
}