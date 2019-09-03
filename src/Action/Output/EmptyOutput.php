<?php

namespace Oapition\Action\Output;

class EmptyOutput
{
    /**
     * @var bool
     */
    public $success = true;

    private function __construct()
    {

    }

    public static function create()
    {
        return new EmptyOutput();
    }
}