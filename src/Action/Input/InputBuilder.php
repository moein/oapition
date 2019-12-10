<?php

namespace Oapition\Action\Input;

use Oapition\Action\Exception\InvalidInput;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\GroupSequenceProviderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class InputBuilder
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     */
    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
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

        $input = $this->serializer->deserialize(json_encode($payload), $inputClass, 'json', ['default_constructor_arguments' => false, ObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true]);

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