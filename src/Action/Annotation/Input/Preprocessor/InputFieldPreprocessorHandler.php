<?php

namespace Oapition\Action\Annotation\Input\Preprocessor;

interface InputFieldPreprocessorHandler
{
    /**
     * @param InputFieldPreprocessor $annotation
     * @param mixed $value
     * @return mixed
     */
    public function handle(InputFieldPreprocessor $annotation, $value);
}