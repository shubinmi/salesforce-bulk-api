<?php

namespace SalesforceBulkApi\dto;

use BaseHelpers\hydrators\ConstructFromArrayOrJson;
use SalesforceBulkApi\exceptions\SFClientException;

class LoginResponseDto extends ConstructFromArrayOrJson
{
    /**
     * @var string
     */
    protected $serverUrl;

    /**
     * @var string
     */
    protected $sessionId;

    /**
     * @var string
     */
    protected $userId;

    /**
     * @var string
     */
    protected $instance;

    public function __construct($params = null)
    {
        if (!$params instanceof \DOMDocument) {
            parent::__construct($params);
            return;
        }
        try {
            $this->serverUrl = $params->getElementsByTagName('serverUrl')[0]->nodeValue;
            $this->sessionId = $params->getElementsByTagName('sessionId')[0]->nodeValue;
            $this->userId    = $params->getElementsByTagName('userId')[0]->nodeValue;
            $this->instance  = explode('.', str_replace('https://', '', $this->serverUrl))[0];
        } catch (\Exception $e) {
            throw new SFClientException('SF Api waiting behavior changed. Parse response error: ' . $e->getMessage());
        }
    }

    /**
     * @return string
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * @return string
     */
    public function getInstance()
    {
        return $this->instance;
    }
}