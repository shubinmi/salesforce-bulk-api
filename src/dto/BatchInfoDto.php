<?php

namespace SalesforceBulkApi\dto;

use BaseHelpers\hydrators\ConstructFromArrayOrJson;

/**
 * For more info follow the link
 * https://developer.salesforce.com/docs/atlas.en-us.api_asynch.meta/api_asynch/asynch_api_reference_batchinfo.htm
 */
class BatchInfoDto extends ConstructFromArrayOrJson
{
    const STATE_QUEUED        = 'Queued';
    const STATE_IN_PROGRESS   = 'InProgress';
    const STATE_COMPLETED     = 'Completed';
    const STATE_FAILED        = 'Failed';
    const STATE_NOT_PROCESSED = 'Not Processed';

    /**
     * @var int
     */
    protected $apexProcessingTime;

    /**
     * @var int
     */
    protected $apiActiveProcessingTime;

    /**
     * @var string
     */
    protected $createdDate;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $jobId;

    /**
     * @var int
     */
    protected $numberRecordsFailed;

    /**
     * @var int
     */
    protected $numberRecordsProcessed;

    /**
     * @var string
     */
    protected $state;

    /**
     * @var string
     */
    protected $stateMessage;

    /**
     * @var string
     */
    protected $systemModstamp;

    /**
     * @var int
     */
    protected $totalProcessingTime;

    /**
     * @return int
     */
    public function getApexProcessingTime()
    {
        return $this->apexProcessingTime;
    }

    /**
     * @return int
     */
    public function getApiActiveProcessingTime()
    {
        return $this->apiActiveProcessingTime;
    }

    /**
     * @return string
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getJobId()
    {
        return $this->jobId;
    }

    /**
     * @return int
     */
    public function getNumberRecordsFailed()
    {
        return $this->numberRecordsFailed;
    }

    /**
     * @return int
     */
    public function getNumberRecordsProcessed()
    {
        return $this->numberRecordsProcessed;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return string
     */
    public function getSystemModstamp()
    {
        return $this->systemModstamp;
    }

    /**
     * @return int
     */
    public function getTotalProcessingTime()
    {
        return $this->totalProcessingTime;
    }

    /**
     * @return string
     */
    public function getStateMessage()
    {
        return $this->stateMessage;
    }

    /**
     * @param string $stateMessage
     *
     * @return $this
     */
    public function setStateMessage($stateMessage)
    {
        $this->stateMessage = $stateMessage;
        return $this;
    }
}