<?php

namespace SalesforceBulkApi\exceptions;

class ApiRequestException extends \Exception
{
    public function getName()
    {
        return 'SFApi request exception';
    }
}