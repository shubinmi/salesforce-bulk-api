<?php

namespace SalesforceBulkApi\Tests\services;

use SalesforceBulkApi\conf\LoginParams;
use SalesforceBulkApi\services\JobSFApiService;

class JobServiceFactory
{
    public static function makeJobService()
    {
        $params = (new LoginParams)
            ->setUserName('shubinmi@gmail.com')
            ->setUserPass('1QazxsW2')
            ->setUserSecretToken('mq8AWRGvOOtymAQyj3tm9DqWL');

        return new JobSFApiService($params);
    }
}