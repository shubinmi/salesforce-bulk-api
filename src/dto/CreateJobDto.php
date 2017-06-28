<?php

namespace SalesforceBulkApi\dto;

use BaseHelpers\hydrators\ConstructFromArrayOrJson;

/**
 * For more info follow the link
 * https://developer.salesforce.com/docs/atlas.en-us.api_asynch.meta/api_asynch/asynch_api_reference_jobinfo.htm
 */
class CreateJobDto extends ConstructFromArrayOrJson
{
    const CONTENT_TYPE_JSON     = 'JSON';
    const CONTENT_TYPE_CSV      = 'CSV';
    const CONTENT_TYPE_XML      = 'XML';
    const CONTENT_TYPE_ZIP_CSV  = 'ZIP_CSV';
    const CONTENT_TYPE_ZIP_JSON = 'ZIP_JSON';
    const CONTENT_TYPE_ZIP_XML  = 'ZIP_XML';

    const OPERATION_INSERT      = 'insert';
    const OPERATION_DELETE      = 'delete';
    const OPERATION_QUERY       = 'query';
    const OPERATION_QUERY_ALL   = 'queryall';
    const OPERATION_UPSERT      = 'upsert';
    const OPERATION_UPDATE      = 'update';
    const OPERATION_HARD_DELETE = 'hardDelete';

    /**
     * @var string
     */
    protected $operation = self::OPERATION_INSERT;

    /**
     * @var string
     */
    protected $object;

    /**
     * @var string
     */
    protected $externalIdFieldName;

    /**
     * @var string
     */
    protected $contentType = self::CONTENT_TYPE_JSON;

    /**
     * @return string
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * @param string $operation
     *
     * @return $this
     */
    public function setOperation($operation)
    {
        $this->operation = $operation;
        return $this;
    }

    /**
     * @return string
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param string $object
     *
     * @return $this
     */
    public function setObject($object)
    {
        $this->object = $object;
        return $this;
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @param string $contentType
     *
     * @return $this
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
        return $this;
    }

    /**
     * @return string
     */
    public function getExternalIdFieldName()
    {
        return $this->externalIdFieldName;
    }

    /**
     * @param string $externalIdFieldName
     *
     * @return $this
     */
    public function setExternalIdFieldName($externalIdFieldName)
    {
        $this->externalIdFieldName = $externalIdFieldName;
        return $this;
    }
}