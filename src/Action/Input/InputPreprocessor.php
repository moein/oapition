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

        foreach ($input as $propertyName => $propertyValue) {
            $property = $reflectionClass->getProperty($propertyName);
            $annotations = $this->reader->getPropertyAnnotations($property);
            foreach ($annotations as $annotation) {
                if ($propertyValue !== null || !$annotation instanceof InputFieldPreprocessor) {
                    continue;
                }

                $handlerClass = get_class($annotation).'Handler';
                /** @var InputFieldPreprocessorHandler $handler */
                $handler = new $handlerClass;
                $input->$propertyName = $handler->handle($annotation, $propertyValue);
            }
        }
    }
}