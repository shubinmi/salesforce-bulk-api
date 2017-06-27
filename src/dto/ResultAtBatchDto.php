<?php

namespace SalesforceBulkApi\dto;

use BaseHelpers\hydrators\ConstructFromArrayOrJson;

class ResultAtBatchDto extends ConstructFromArrayOrJson
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var bool
     */
    protected $success;

    /**
     * @var bool
     */
    protected $created;

    /**
     * For resultId from Query batch
     *
     * @var string
     */
    protected $result;

    /**
     * @var array
     */
    protected $errors;

    public function __construct($params = null)
    {
        parent::__construct($params);
        if (empty($this->id) && is_string($params)) {
            $this->result = $params;
        }
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * @param bool $success
     *
     * @return $this
     */
    protected function setSuccess($success)
    {
        $this->success = (bool)$success;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCreated()
    {
        return $this->created;
    }

    /**
     * @param bool $created
     *
     * @return $this
     */
    protected function setCreated($created)
    {
        $this->created = (bool)$created;
        return $this;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}