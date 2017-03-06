<?php

namespace SalesforceBulkApi\objects;

use SalesforceBulkApi\dto\BatchInfoDto;

class SFBatchErrors
{
    /**
     * @var BatchInfoDto
     */
    private $batchInfo;

    /**
     * @var int[]
     */
    private $errorNumbers;

    /**
     * @var string[]
     */
    private $errorMessages;

    /**
     * @param int    $number
     * @param string $message
     *
     * @return $this
     */
    public function addError($number, $message)
    {
        $this->errorNumbers[]  = $number;
        $this->errorMessages[] = $message;

        return $this;
    }

    /**
     * @return BatchInfoDto
     */
    public function getBatchInfo()
    {
        return $this->batchInfo;
    }

    /**
     * @return \int[]
     */
    public function getErrorNumbers()
    {
        return $this->errorNumbers;
    }

    /**
     * @return \string[]
     */
    public function getErrorMessages()
    {
        return $this->errorMessages;
    }

    /**
     * @param BatchInfoDto $batchInfo
     *
     * @return $this
     */
    public function setBatchInfo($batchInfo)
    {
        $this->batchInfo = $batchInfo;
        return $this;
    }
}