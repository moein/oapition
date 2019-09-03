<?php

namespace Oapition\Action\Exception;

class ActionCallException extends \RuntimeException
{
    /**
     * @var mixed
     */
    private $errorCode;

    /**
     * @var mixed
     */
    private $errorDetails;

    /**
     * @param $errorCode
     * @param $errorDetails
     */
    public function __construct($errorCode, $errorDetails)
    {
        $this->errorCode = $errorCode;
        $this->errorDetails = $errorDetails;
    }

    /**
     * @return mixed
     */
    public function errorCode()
    {
        return $this->errorCode;
    }

    /**
     * @return mixed
     */
    public function errorDetails()
    {
        return $this->errorDetails;
    }
}