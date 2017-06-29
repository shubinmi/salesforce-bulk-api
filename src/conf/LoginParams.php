<?php

namespace SalesforceBulkApi\conf;

use BaseHelpers\hydrators\ConstructFromArrayOrJson;

class LoginParams extends ConstructFromArrayOrJson
{
    /**
     * @var string
     */
    protected $userName;

    /**
     * @var string
     */
    protected $userPass;

    /**
     * @var string
     */
    protected $userSecretToken;

    /**
     * @var string
     */
    protected $apiVersion = '39.0';

    /**
     * @var bool
     */
    protected $asPartner = true;

    /**
     * @var string
     */
    protected $endpointPrefix = 'login';
    
    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @param string $userName
     *
     * @return $this
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserPass()
    {
        return $this->userPass;
    }

    /**
     * @param string $userPass
     *
     * @return $this
     */
    public function setUserPass($userPass)
    {
        $this->userPass = $userPass;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserSecretToken()
    {
        return $this->userSecretToken;
    }

    /**
     * @param string $userSecretToken
     *
     * @return $this
     */
    public function setUserSecretToken($userSecretToken)
    {
        $this->userSecretToken = $userSecretToken;
        return $this;
    }

    /**
     * @return string
     */
    public function getApiVersion()
    {
        return $this->apiVersion;
    }

    /**
     * @param string $apiVersion
     *
     * @return $this
     */
    public function setApiVersion($apiVersion)
    {
        $this->apiVersion = $apiVersion;
        return $this;
    }

    /**
     * @return bool
     */
    public function amIPartner()
    {
        return $this->asPartner;
    }

    /**
     * @return bool
     */
    public function amIEnterprise()
    {
        return !$this->asPartner;
    }

    /**
     * @return $this
     */
    public function setAsPartner()
    {
        $this->asPartner = true;
        return $this;
    }

    /**
     * @return $this
     */
    public function setAsEnterprise()
    {
        $this->asPartner = false;
        return $this;
    }
    
     /**
     * @return string
     */
    public function getEndpointPrefix()
        {
        return $this->endpointPrefix;
        }

    /**
     * @return $this
     */
    public function setEndpointPrefixAsProduction()
        {
        $this->endpointPrefix = 'login';
        return $this;
        }

    /**
     * @return $this
     */
    public function setEndpointPrefixAsSandbox()
        {
        $this->endpointPrefix = 'test';
        return $this;
        }

    /**
     * @param string $endpointPrefix
     *
     * @return $this
     */
    public function setEndpointPrefix($endpointPrefix)
        {
        $this->endpointPrefix = $endpointPrefix;
        return $this;
        }
}
