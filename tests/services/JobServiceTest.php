<?php

namespace SalesforceBulkApi\Tests\services;

use PHPUnit\Framework\TestCase;
use SalesforceBulkApi\dto\CreateJobDto;

class JobServiceTest extends TestCase
{
    /**
     * @return array
     * @throws \Exception
     */
    public function testInsert()
    {
        $email1 = 'mr.smith' . time() . '@qa' . rand(1000, 9999) . '.com';
        $email2 = 'msr.smith' . time() . '@qa' . rand(1000, 9999) . '.com';
        $john = [
            'Email'             => $email1,
            'FirstName'         => 'Brad',
            'LastName'          => 'Smith',
            'Company'           => 'SFPHPClient',
            'Title'             => 'QA',
            'Phone'             => '+1 123 123 1231',
            'NumberOfEmployees' => '2',
            'Description'       => 'These fuckers get younger every year.'
        ];
        $jane = [
            'Email'             => $email2,
            'FirstName'         => 'Angelina',
            'LastName'          => 'Smith',
            'Company'           => 'SFPHPClient',
            'Title'             => 'QA',
            'Phone'             => '+1 123 123 1234',
            'NumberOfEmployees' => '2',
            'Description'       => 'Happy endings are just stories that haven\'t finished yet.'
        ];
        $data1 = [$john];
        $data2 = [$jane];
        $jobRequest = (new CreateJobDto)
            ->setObject('Lead')
            ->setOperation(CreateJobDto::OPERATION_INSERT);
        $insertJob = JobServiceFactory::makeJobService();
        $errors = $insertJob->initJob($jobRequest)
            ->addBatchToJob($data1)
            ->addBatchToJob($data2)
            ->closeJob()
            ->waitingForComplete()
            ->getErrors();

        $this->assertTrue(empty($errors));

        return ['john' => $john, 'jane' => $jane];
    }

    /**
     * @depends testInsert
     *
     * @param array $insertedData
     *
     * @return array
     * @throws \Exception
     */
    public function testQueryAfterInsert(array $insertedData)
    {
        $john = $insertedData['john'];
        $jane = $insertedData['jane'];
        $jobRequest = (new CreateJobDto)
            ->setObject('Lead')
            ->setOperation(CreateJobDto::OPERATION_QUERY);
        $selectJob = JobServiceFactory::makeJobService();
        /** @noinspection SqlDialectInspection */
        /** @noinspection SqlNoDataSourceInspection */
        $errors = $selectJob->initJob($jobRequest)
            ->addQueryBatchToJob("SELECT Email,FirstName FROM Lead")
            ->closeJob()
            ->waitingForComplete()
            ->getErrors();

        $this->assertTrue(empty($errors));

        $selectJob->getResults();
        $jobResults = $selectJob->getQueriesResults();
        $batchesResults = current($jobResults);
        $gotJohnData = false;
        $gotJaneData = false;
        foreach ($batchesResults as $results) {
            foreach ($results as $item) {
                if ($item['Email'] == $john['Email']
                    && $item['FirstName'] == $john['FirstName']) {
                    $gotJohnData = true;
                } elseif ($item['Email'] == $jane['Email']
                    && $item['FirstName'] == $jane['FirstName']) {
                    $gotJaneData = true;
                }
            }
        }

        $this->assertTrue($gotJohnData);
        $this->assertTrue($gotJaneData);

        return $insertedData;
    }

    /**
     * @depends testQueryAfterInsert
     *
     * @param array $insertedData
     *
     * @return array
     * @throws \Exception
     */
    public function testUpsert(array $insertedData)
    {
        $insertedData['john']['FirstName'] = 'John';
        $insertedData['jr'] = [
            'Email'             => 'john.jr.smith' . time() . '@qa' . rand(
                    1000, 9999
                ) . '.com',
            'FirstName'         => 'John Jr.',
            'LastName'          => 'Smith',
            'Company'           => 'SFPHPClient',
            'Title'             => 'QA',
            'Phone'             => '+1 123 123 1231',
            'NumberOfEmployees' => '2',
            'Description'       => 'These fuckers get younger every year.'
        ];

        $jobRequest = (new CreateJobDto)
            ->setObject('Lead')
            ->setOperation(CreateJobDto::OPERATION_UPSERT)
            ->setExternalIdFieldName('Email');
        $upsertJob = JobServiceFactory::makeJobService();
        $errors = $upsertJob->initJob($jobRequest)
            ->addBatchToJob([$insertedData['john'], $insertedData['jr']])
            ->closeJob()
            ->waitingForComplete()
            ->getErrors();

        $this->assertTrue(empty($errors));

        return $insertedData;
    }

    /**
     * @depends testUpsert
     *
     * @param array $insertedData
     *
     * @return array
     * @throws \Exception
     */
    public function testQueryAfterUpsert(array $insertedData)
    {
        $john = $insertedData['john'];
        $jr = $insertedData['jr'];
        $jobRequest = (new CreateJobDto)
            ->setObject('Lead')
            ->setOperation(CreateJobDto::OPERATION_QUERY);
        $selectJob = JobServiceFactory::makeJobService();
        /** @noinspection SqlDialectInspection */
        /** @noinspection SqlNoDataSourceInspection */
        $errors = $selectJob->initJob($jobRequest)
            ->addQueryBatchToJob("SELECT Email,FirstName FROM Lead")
            ->closeJob()
            ->waitingForComplete()
            ->getErrors();

        $this->assertTrue(empty($errors));

        $selectJob->getResults();
        $jobResults = $selectJob->getQueriesResults();
        $batchesResults = current($jobResults);
        $gotJohnData = false;
        $gotJrData = false;
        foreach ($batchesResults as $results) {
            foreach ($results as $item) {
                if ($item['Email'] == $john['Email']
                    && $item['FirstName'] == $john['FirstName']) {
                    $gotJohnData = true;
                } elseif ($item['Email'] == $jr['Email']
                    && $item['FirstName'] == $jr['FirstName']) {
                    $gotJrData = true;
                }
            }
        }

        $this->assertTrue($gotJohnData);
        $this->assertTrue($gotJrData);

        return $insertedData;
    }

    /**
     * @throws \Exception
     */
    public function testErrorOnQuery()
    {
        $jobRequest = (new CreateJobDto)
            ->setObject('Lead')
            ->setOperation(CreateJobDto::OPERATION_QUERY);
        $selectJob = JobServiceFactory::makeJobService();
        /** @noinspection SqlDialectInspection */
        /** @noinspection SqlNoDataSourceInspection */
        $errors = $selectJob->initJob($jobRequest)
            ->addQueryBatchToJob("SELECT Email1,FirstName FROM Lead")
            ->closeJob()
            ->waitingForComplete()
            ->getErrors();

        $this->assertTrue(!empty($errors));
        $this->assertContains(
            'INVALID_FIELD', $errors[0]->getBatchInfo()->getStateMessage()
        );
    }
}