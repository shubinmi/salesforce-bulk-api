# Client for Salesforce Bulk Api 
## Easy way to manipulate your Salesforce data

Don't worry, Be happy

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/shubinmi/salesforce-bulk-api/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/shubinmi/salesforce-bulk-api/?branch=master) [![Latest Stable Version](https://img.shields.io/packagist/v/shubinmi/salesforce-bulk-api.svg)](https://packagist.org/packages/shubinmi/salesforce-bulk-api) [![Maintainability](https://api.codeclimate.com/v1/badges/c892e96e789572222762/maintainability)](https://codeclimate.com/github/shubinmi/salesforce-bulk-api/maintainability) [![Open Source Love](https://badges.frapsoft.com/os/v2/open-source.svg?v=103)](https://github.com/shubinmi/salesforce-bulk-api) [![MIT Licence](https://badges.frapsoft.com/os/mit/mit.svg?v=103)](https://opensource.org/licenses/mit-license.php) [![Build Status](https://travis-ci.org/shubinmi/salesforce-bulk-api.svg?branch=master)](https://travis-ci.org/shubinmi/salesforce-bulk-api) [![Codacy Badge](https://api.codacy.com/project/badge/Grade/f298666aa7424dffab832342af6646db)](https://www.codacy.com/app/shubinmi/salesforce-bulk-api?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=shubinmi/salesforce-bulk-api&amp;utm_campaign=Badge_Grade)

## Features

- INSERT job
- UPDATE job
- UPSERT job
- DELETE job
- QUERY (SELECT) job

#### For example see to the [tests](https://github.com/shubinmi/salesforce-bulk-api/blob/master/tests/services/JobServiceTest.php)

## Installation

Install the latest version with

```bash
$ composer require shubinmi/salesforce-bulk-api
```

## Basic Usage

```php
<?php

use SalesforceBulkApi\dto\CreateJobDto;
use SalesforceBulkApi\objects\SFBatchErrors;
use SalesforceBulkApi\conf\LoginParams;
use SalesforceBulkApi\services\JobSFApiService;

// Set up API Client
$params = (new LoginParams)
    ->setUserName('mySFLogin')
    ->setUserPass('MySFPass')
    ->setUserSecretToken('mySecretTokenFomSF');

// (optional) Flag as Sandbox
// $params->setEndpointPrefixAsSandbox();

// Set up SF job
$jobRequest = (new CreateJobDto)
    ->setObject('My_User__c')
    ->setOperation(CreateJobDto::OPERATION_INSERT); // Use CreateJobDto::OPERATION_UPSERT for upsert operation

// (optional if Upsert) Set an External Id
// $upsertKey = 'My_External_Id__c';
// $jobRequest->setExternalIdFieldName($upsertKey);

// Data Batches
$data = [
    [ // Batch 1
        [
            'Email__c' => 'new@user.net',
            'First_Name__c' => 'New Net'
        ],
        [
            'Email__c' => 'new@user.org',
            'First_Name__c' => 'New Org'
        ],
    ],
    [ // Batch 2
        [
            'Email__c' => 'new1@user.net',
            'First_Name__c' => 'New1 Net'
        ],
        [
            'Email__c' => 'new1@user.org',
            'First_Name__c' => 'New1 Org'
        ],
    ],
    [ // Batch 3
        [
            'Email__c' => 'new2@user.net',
            'First_Name__c' => 'New2 Net'
        ],
        [
            'Email__c' => 'new2@user.org',
            'First_Name__c' => 'New2 Org'
        ],
    ],
];

// Init Job
$jobService = (new JobSFApiService($params))
    ->initJob($jobRequest);

// Add batches of data, can be up to 10000 records long each
foreach ($data as $batchData) {
    $jobService->addBatchToJob($batchData);
}

// Gather up an ordered list of Batch ids to reference data in the batch, specifically on error handling
// JobSFApiService::waitingForComplete update job statuses in the order returned from Salesforce
// This new order is not necessarily the same order the data was submitted in making referencing the original data difficult
$job = $jobService->getJob();
$batchesInfo = $job->getBatchesInfo();
$batchIdReference = array_flip(array_map(function($batchInfoDto){
    return $batchInfoDto->getId();
}, $batchesInfo));

// Close Job and Wait for Job completion
$jobService
    ->closeJob()
    ->waitingForComplete();

// Collect jobs errors
$errors = $jobService->getErrors();

// Operate with errors
foreach ($errors as $error) {
    
    /** @var SFBatchErrors $error */
    $errorsBatch         = $error->getBatchInfo();
    $batchId             = $errorsBatch->getId();
    $batchNo             = $batchIdReference[$batchId];
    $errorsMsg           = $error->getErrorMessages();
    $errorsElementNumber = $error->getErrorNumbers();

    if (empty($errorsElementNumber)) {
        // No specific errors
        echo "Batch $batchId (#$batchNo) returned a general error" . PHP_EOL;
        echo "\tState: " . $errorsBatch->getState() . ' (' . $errorsBatch->getStateMessage() . ')' . PHP_EOL;
        echo "\tNote: An error here might mean the data types sent are incorrect (eg \"0\" vs 0/false)." . PHP_EOL;
    } else {
        echo "Batch $batchId (#$batchNo) failed for following rows:" . PHP_EOL;
        foreach ($errorsElementNumber as $errorMsgKey => $errorRowNumber) {
            echo "\tRow number = " . $errorRowNumber . " Error message = " . $errorsMsg[$errorMsgKey] . PHP_EOL;
            $record = $data[$batchNo][$errorRowNumber];
            echo "\t\tEmail = " . $record['Email__c'] . PHP_EOL;
            echo "\t\tFirst Name = " .  $record['First_Name__c'] . PHP_EOL;
        }
    }
}
```

## Contribute safely

```bash
$ sh ./vendor/phpunit/phpunit/phpunit ./tests/services
```