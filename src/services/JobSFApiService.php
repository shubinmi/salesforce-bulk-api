<?php

namespace SalesforceBulkApi\services;

use SalesforceBulkApi\api\BatchApiSF;
use SalesforceBulkApi\api\JobApiSF;
use SalesforceBulkApi\conf\LoginParams;
use SalesforceBulkApi\dto\BatchInfoDto;
use SalesforceBulkApi\dto\CreateJobDto;
use SalesforceBulkApi\dto\ResultAtBatchDto;
use SalesforceBulkApi\objects\SFBatchErrors;
use SalesforceBulkApi\objects\SFJob;

class JobSFApiService
{
    /**
     * @var ApiSalesforce
     */
    private $api;

    /**
     * @var SFJob
     */
    private $job;

    /**
     * @param LoginParams $params
     * @param array       $guzzleHttpClientConfig
     */
    public function __construct(
        LoginParams $params, array $guzzleHttpClientConfig = ['timeout' => 3]
    ) {
        $this->api = new ApiSalesforce($params, $guzzleHttpClientConfig);
    }

    /**
     * @param CreateJobDto $dto
     *
     * @return $this
     * @throws \Exception
     */
    public function initJob(CreateJobDto $dto)
    {
        $this->job = new SFJob();
        $this->job->setJobInfo(JobApiSF::create($this->api, $dto));

        return $this;
    }

    /**
     * @param array $data
     *
     * @return $this
     * @throws \Exception
     */
    public function addBatchToJob(array $data)
    {
        return $this->addQueryBatchToJob(json_encode($data));
    }

    /**
     * @param string $query
     *
     * @return $this
     * @throws \Exception
     */
    public function addQueryBatchToJob($query)
    {
        $batch = BatchApiSF::addToJob(
            $this->api, $this->job->getJobInfo(), $query
        );
        $this->job->addBatchInfo($batch);

        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function closeJob()
    {
        $job = JobApiSF::close($this->api, $this->job->getJobInfo());
        $this->job->setJobInfo($job);

        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function waitingForComplete()
    {
        $batches = BatchApiSF::infoForAllInJob(
            $this->api, $this->job->getJobInfo()
        );
        $this->job->setBatchesInfo($batches);
        foreach ($batches as $batch) {
            if (in_array(
                $batch->getState(),
                [BatchInfoDto::STATE_IN_PROGRESS, BatchInfoDto::STATE_QUEUED]
            )) {
                sleep(rand(1, 3));
                return $this->waitingForComplete();
            }
        }

        return $this;
    }

    /**
     * @return ResultAtBatchDto[][]
     * @throws \Exception
     */
    public function getResults()
    {
        $results = [];
        foreach ($this->job->getBatchesInfo() as $batchInfoDto) {
            $results[$batchInfoDto->getId()] = BatchApiSF::results(
                $this->api, $batchInfoDto
            );
        }
        $this->job->setBatchesResults($results);

        return $results;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getQueriesResults()
    {
        if (!$this->job->getBatchesResults()) {
            return [];
        }
        $queriesResults = [];
        foreach ($this->job->getBatchesResults() as $batchId => $results) {
            $queriesResults[$batchId] = [];
            /** @var ResultAtBatchDto $result */
            foreach ($results as $result) {
                $resultId = $result->getId() ?: $result->getResult();
                $batchesInfo = $this->job->getBatchesInfo();
                if (empty($batchesInfo[$batchId])) {
                    return $queriesResults;
                }
                $queriesResults[$batchId][$resultId] = BatchApiSF::result(
                    $this->api, $batchesInfo[$batchId], $resultId
                );
            }
        }

        return $queriesResults;
    }

    /**
     * @return SFBatchErrors[]
     * @throws \Exception
     */
    public function getErrors()
    {
        $errors = [];
        foreach ($this->job->getBatchesInfo() as $batchInfoDto) {
            $error = new SFBatchErrors();
            $error->setBatchInfo($batchInfoDto);
            if ($batchInfoDto->getState() != BatchInfoDto::STATE_COMPLETED) {
                if ($batchInfoDto->getState() == BatchInfoDto::STATE_FAILED) {
                    $errors[] = $error;
                    continue;
                }
            }
            $results = $this->job->getBatchesResults();
            if (empty($results[$batchInfoDto->getId()])) {
                $results = BatchApiSF::results($this->api, $batchInfoDto);
            } else {
                $results = $results[$batchInfoDto->getId()];
            }
            $i = 0;
            foreach ($results as $result) {
                if (!$result->isSuccess() && $result->getErrors()) {
                    $error->addError($i, json_encode($result->getErrors()));
                }
                ++$i;
            }
            if (!empty($error->getErrorNumbers())) {
                $errors[] = $error;
            }
        }

        return $errors;
    }

    /**
     * @return SFJob
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * @return string
     */
    public function getJobId()
    {
        return $this->job->getJobInfo()->getId();
    }

    /**
     * @return string
     */
    public function getJobObject()
    {
        return $this->job->getJobInfo()->getObject();
    }
}