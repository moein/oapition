<?php

namespace Oapition\Action\Input;

use Doctrine\Common\Annotations\Reader;
use Oapition\Action\Annotation\Input\Preprocessor\InputFieldPreprocessor;
use Oapition\Action\Annotation\Input\Preprocessor\InputFieldPreprocessorHandler;

class InputPreprocessor
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    public function process($input)
    {
        $reflectionClass = new \ReflectionClass(get_class($input));

        foreach ($input as $propertyName) {
            $property = $reflectionClass->getProperty($propertyName);
            $annotations = $this->reader->getPropertyAnnotations($property);
            foreach ($annotations as $annotation) {
                if (!$annotation instanceof InputFieldPreprocessor) {
                    continue;
                }

                /** @var InputFieldPreprocessorHandler $handlerClass */
                $handlerClass = get_class($annotation).'Handler';
                $value = $input->$propertyName;
                if ($value !== null) {
                    $input->$propertyName = $handlerClass->handle($annotation, $value);
                }
            }
        }
    }
}