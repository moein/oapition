<?php

namespace Oapition\Handler\Exception;

class ActionNotFound extends \RuntimeException
{
    /**
     * @var string
     */
    private $actionName;

    /**
     * @param string $actionName
     */
    public function __construct(string $actionName)
    {
        $this->actionName = $actionName;
    }
}