<?php

namespace SalesforceBulkApi\conf;

use common\library\hydrators\ConstructFromArrayOrJson;

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
}