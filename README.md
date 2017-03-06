# Salesforce Bulk Api 
## Easy way to manipulate your Salesforce data

Don't worry, Be happy

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

$params = (new LoginParams)
    ->setUserName('mySFLogin')
    ->setUserPass('MySFPass')
    ->setUserSecretToken('mySecretTokenFomSF');

$jobRequest = (new CreateJobDto)
    ->setObject('My_User__c')
    ->setOperation(CreateJobDto::OPERATION_INSERT);

$insertJob = new JobSFApiService($params);
$insertJob->initJob($jobRequest);

$data = [
    [
        'Email__c' => 'new@user.net',
        'First_Name__c' => 'New Net'
    ],
    [
        'Email__c' => 'new@user.org',
        'First_Name__c' => 'New Org'
    ]
];
$insertJob
    ->addBatchToJob($data)
    ->closeJob();

$jobRequest
    ->setOperation(CreateJobDto::OPERATION_UPSERT)
    ->setExternalIdFieldName('Email__c');

$upsertJob = new JobSFApiService($params);
$upsertJob->initJob($jobRequest);

$data = [
    [
        'Email__c' => 'new@user.net',
        'First_Name__c' => 'Not new Net'
    ],
    [
        'Email__c' => 'new@user.com',
        'First_Name__c' => 'New Com'
    ]
];
$upsertJob
    ->addBatchToJob($data)
    ->closeJob();

$errorsOnInsert = $insertJob->waitingForComplete()->getErrors();
$errorsOnUpsert = $upsertJob->waitingForComplete()->getErrors();

foreach ($errorsOnInsert as $error) {
    /** @var SFBatchErrors $error */
    $errorsBatch         = $error->getBatchInfo();
    $errorsMsg           = $error->getErrorMessages();
    $errorsElementNumber = $error->getErrorNumbers();
    if (empty($errorsElementNumber)) {
        echo 'Batch ' . $errorsBatch->getId() . ' return ' . $errorsBatch->getState() . PHP_EOL;
    } else {
        echo 'Batch ' . $errorsBatch->getId() . ' fail for next rows:' . PHP_EOL;
        foreach ($errorsElementNumber as $errorMsgKey => $errorRowNumber) {
            echo 'Row number = ' . $errorRowNumber 
                . ' Error message = ' . $errorsMsg[$errorMsgKey] . PHP_EOL;
        }
    }
}

```