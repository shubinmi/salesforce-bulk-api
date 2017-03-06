<?php

namespace SalesforceBulkApi\exceptions;

class ApiResponseException extends \Exception
{
    public function getName()
    {
        return 'SFApi exception from API response';
    }
}