<?php

namespace Oapition\Action\Input;

use Oapition\Action\Exception\InvalidInput;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\GroupSequenceProviderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class InputBuilder
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var InputPreprocessor
     */
    private $inputPreprocessor;

    /**
     * @param ValidatorInterface $validator
     * @param InputPreprocessor $inputPreprocessor
     */
    public function __construct(ValidatorInterface $validator, InputPreprocessor $inputPreprocessor)
    {
        $this->validator = $validator;
        $this->inputPreprocessor = $inputPreprocessor;
    }

    /**
     * @param object $action
     * @param array $payload
     * @return null|object
     */
    public function build(object $action, array $payload)
    {
        $inputClass = $action instanceof CustomInputClass ? $action->getInputClass() : get_class($action).'Input';
        if (!class_exists($inputClass)) {
            return null;
        }

        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $input = $serializer->deserialize(json_encode($payload), $inputClass, 'json', ['default_constructor_arguments' => false, ObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true]);
        $this->inputPreprocessor->process($input);

        $groups = $input instanceof GroupSequenceProviderInterface ? $input->getGroupSequence() : null;
        $validationResult = $this->validator->validate($input, null, $groups);
        if (count($validationResult) === 0) {
            return $input;
        }

        $errors = [];
        /** @var ConstraintViolationInterface $item */
        foreach ($validationResult as $item) {
            $errors[] = ['path' => $item->getPropertyPath(), 'error' => $item->getMessage()];
        }

        throw new InvalidInput($errors);
    }
}