<?php

namespace SalesforceBulkApi\exceptions;

class SFClientException extends \Exception
{
    public function getName()
    {
        return 'SFApi exception';
    }
}