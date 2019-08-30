<?php

namespace Oapition\Action\Output;

class Output
{
    /**
     * @var mixed
     */
    public $result;

    /**
     * @var Error
     */
    public $error;

    /**
     * @param mixed $result
     * @param Error $error
     */
    public function __construct($result, Error $error)
    {
        $this->result = $result;
        $this->error = $error;
    }

    /**
     * @return mixed
     */
    public function result()
    {
        return $this->result;
    }

    /**
     * @return Error
     */
    public function error()
    {
        return $this->error;
    }
}