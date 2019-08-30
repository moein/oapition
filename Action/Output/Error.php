<?php

namespace Oapition\Action\Output;

class Error
{
    /**
     * @var mixed
     */
    public $code;

    /**
     * @var mixed
     */
    public $details;

    /**
     * @param mixed $code
     * @param mixed $details
     */
    public function __construct($code, $details)
    {
        $this->code = $code;
        $this->details = $details;
    }
}