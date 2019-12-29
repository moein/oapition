<?php

namespace Oapition\Action\Annotation\Input\Preprocessor;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
class Trim implements InputFieldPreprocessor
{
    /**
     * @var string
     * @Enum({"LEFT", "RIGHT", "BOTH"})
     */
    public $sides = 'BOTH';

    /**
     * @var string
     */
    public $charlist;
}