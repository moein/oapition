<?php

namespace Oapition\Action\Exception;

use Throwable;

class ActionNotCallableObject extends InvalidAction
{
    public function __construct(string $type)
    {
        parent::__construct(sprintf('Expected callable object but got %s', $type));
    }
}