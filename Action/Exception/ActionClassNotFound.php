<?php

namespace Oapition\Action\Exception;

class ActionClassNotFound extends InvalidAction
{
    /**
     * @param string $class
     */
    public function __construct(string $class)
    {
        parent::__construct(sprintf('Could not find an action with class %s. Maybe you forgot to implement Oapition\\Action\\Action?', $class));
    }
}