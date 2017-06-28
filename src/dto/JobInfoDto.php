<?php

namespace SalesforceBulkApi\dto;

use BaseHelpers\hydrators\ConstructFromArrayOrJson;

/**
 * For more info follow the link
 * https://developer.salesforce.com/docs/atlas.en-us.api_asynch.meta/api_asynch/asynch_api_reference_jobinfo.htm
 */
class JobInfoDto extends ConstructFromArrayOrJson
{
    const STATE_OPEN    = 'Open';
    const STATE_CLOSED  = 'Closed';
    const STATE_ABORTED = 'Aborted';
    const STATE_FAILED  = 'Failed';

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
    protected $apiVersion;

    /**
     * @var string
     */
    protected $concurrencyMode;

    /**
     * @var string
     */
    protected $contentType;

    /**
     * @var string
     */
    protected $createdById;

    /**
     * @var string
     */
    protected $createdDate;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var int
     */
    protected $numberBatchesCompleted;

    /**
     * @var int
     */
    protected $numberBatchesFailed;

    /**
     * @var int
     */
    protected $numberBatchesInProgress;

    /**
     * @var int
     */
    protected $numberBatchesQueued;

    /**
     * @var int
     */
    protected $numberBatchesTotal;

    /**
     * @var int
     */
    protected $numberRecordsFailed;

    /**
     * @var int
     */
    protected $numberRecordsProcessed;

    /**
     * @var int
     */
    protected $numberRetries;

    /**
     * @var string
     */
    protected $object;

    /**
     * @var string
     */
    protected $operation;

    /**
     * @var string
     */
    protected $state;

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
    public function getApiVersion()
    {
        return $this->apiVersion;
    }

    /**
     * @return string
     */
    public function getConcurrencyMode()
    {
        return $this->concurrencyMode;
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @return string
     */
    public function getCreatedById()
    {
        return $this->createdById;
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
     * @return int
     */
    public function getNumberBatchesCompleted()
    {
        return $this->numberBatchesCompleted;
    }

    /**
     * @return int
     */
    public function getNumberBatchesFailed()
    {
        return $this->numberBatchesFailed;
    }

    /**
     * @return int
     */
    public function getNumberBatchesInProgress()
    {
        return $this->numberBatchesInProgress;
    }

    /**
     * @return int
     */
    public function getNumberBatchesQueued()
    {
        return $this->numberBatchesQueued;
    }

    /**
     * @return int
     */
    public function getNumberBatchesTotal()
    {
        return $this->numberBatchesTotal;
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
     * @return int
     */
    public function getNumberRetries()
    {
        return $this->numberRetries;
    }

    /**
     * @return string
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @return string
     */
    public function getOperation()
    {
        return $this->operation;
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
}