<?php

namespace Oapition\Action\Input;

use Doctrine\Common\Annotations\AnnotationReader;
use Oapition\Action\Annotation\Input\Preprocessor\InputFieldPreprocessor;
use Oapition\Action\Annotation\Input\Preprocessor\InputFieldPreprocessorHandler;

class InputPreprocessor
{
    /**
     * @var AnnotationReader
     */
    private $annotationReader;

    /**
     * @param AnnotationReader $annotationReader
     */
    public function __construct(AnnotationReader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    public function process($input)
    {
        $reflectionClass = new \ReflectionClass(get_class($input));

        foreach ($input as $propertyName) {
            $property = $reflectionClass->getProperty($propertyName);
            $annotations = $this->annotationReader->getPropertyAnnotations($property);
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