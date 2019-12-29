<?php

namespace Oapition\Action\Annotation\Input\Preprocessor;

use Oapition\Action\Annotation\UnexpectedTypeException;

class TrimHandler implements InputFieldPreprocessorHandler
{
    /**
     * @inheritdoc
     */
    public function handle(InputFieldPreprocessor $trim, $value)
    {
        if (!$trim instanceof Trim) {
            throw new UnexpectedTypeException($trim, Trim::class);
        }

        switch ($trim->sides) {
            case 'LEFT':
                return ltrim($value, $trim->charlist);
            case 'RIGHT':
                return rtrim($value, $trim->charlist);
            case 'BOTH':
                return trim($value, $trim->charlist);
        }
    }
}