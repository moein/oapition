<?php

namespace Oapition\Action\Exception;

class InvalidInput extends ActionCallException
{
    /**
     * @var array
     * An array of arrays with keys of path and error
     */
    private $errors;

    /**
     * @param array $errors
     */
    public function __construct(array $errors)
    {
        parent::__construct('invalid_input', $errors);
        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}