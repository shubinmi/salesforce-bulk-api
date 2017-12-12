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
            ->setUserPass('1QazxsW2#')
            ->setUserSecretToken('ipx7vH84bN2WqyY40Tfk3nnT');

        return new JobSFApiService($params);
    }
}