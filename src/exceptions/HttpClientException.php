<?php

namespace SalesforceBulkApi\exceptions;

class HttpClientException extends \Exception
{
    public function getName()
    {
        return 'SFApi exception from HttpClient';
    }
}