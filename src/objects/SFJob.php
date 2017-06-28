<?php

namespace SalesforceBulkApi\objects;

use SalesforceBulkApi\dto\BatchInfoDto;
use SalesforceBulkApi\dto\JobInfoDto;
use SalesforceBulkApi\dto\ResultAtBatchDto;

class SFJob
{
    /**
     * @var JobInfoDto
     */
    private $jobInfo;

    /**
     * @var BatchInfoDto[]
     */
    private $batchesInfo;

    /**
     * @var ResultAtBatchDto[][]
     */
    private $batchesResults;

    /**
     * @return JobInfoDto
     */
    public function getJobInfo()
    {
        return $this->jobInfo;
    }

    /**
     * @param JobInfoDto $jobInfo
     *
     * @return $this
     */
    public function setJobInfo($jobInfo)
    {
        $this->jobInfo = $jobInfo;
        return $this;
    }

    /**
     * @return BatchInfoDto[]
     */
    public function getBatchesInfo()
    {
        return $this->batchesInfo;
    }

    /**
     * @param BatchInfoDto[] $batchesInfo
     *
     * @return $this
     */
    public function setBatchesInfo($batchesInfo)
    {
        $this->batchesInfo = $batchesInfo;
        return $this;
    }

    /**
     * @param BatchInfoDto $dto
     *
     * @return $this
     */
    public function addBatchInfo(BatchInfoDto $dto)
    {
        $this->batchesInfo[] = $dto;
        return $this;
    }

    /**
     * @return ResultAtBatchDto[][]
     */
    public function getBatchesResults()
    {
        return $this->batchesResults;
    }

    /**
     * @param ResultAtBatchDto[][] $batchesResults
     *
     * @return $this
     */
    public function setBatchesResults(array $batchesResults)
    {
        $this->batchesResults = $batchesResults;
        return $this;
    }
}