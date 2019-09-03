<?php

namespace Oapition\Handler;

use Oapition\Action\Exception\ActionCallException;
use Oapition\Action\ActionRunnerBuilder;
use Oapition\Action\Output\Error;
use Oapition\Action\Output\Output;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;

class JsonHandler
{
    /**
     * @var ActionRunnerBuilder
     */
    private $actionRunnerBuilder;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var array
     * An array of actionName => actionClass
     */
    private $actionClasses = [];

    /**
     * @param ActionRunnerBuilder $actionRunnerBuilder
     * @param SerializerInterface $serializer
     */
    public function __construct(ActionRunnerBuilder $actionRunnerBuilder, SerializerInterface $serializer)
    {
        $this->actionRunnerBuilder = $actionRunnerBuilder;
        $this->serializer = $serializer;
    }

    /**
     * @param string $actionName
     * @param string $actionClass
     */
    public function addAction(string $actionName, string $actionClass)
    {
        $this->actionClasses[$actionName] = $actionClass;
    }

    /**
     * @param array $actions
     */
    public function addActions(array $actions)
    {
        foreach ($actions as $name => $class) {
            $this->addAction($name, $class);
        }
    }

    /**
     * @param Request $request
     * @param null|UserInterface $user
     * @return JsonResponse
     */
    public function handle(Request $request, ?UserInterface $user): JsonResponse
    {
        $body = $request->getContent();
        $parsedBody = json_decode($body, true);
        $actionName = $parsedBody['action'] ?? '';
        $payload = $parsedBody['payload'] ?? [];
        $actionClass = $this->actionClasses[$actionName] ?? '';
        if ($actionClass === '') {
            return $this->serialize(null, 'invalid_action', null);
        }

        try {
            $actionRunner = $this->actionRunnerBuilder->build($actionClass, $payload, $user);
            $output = $actionRunner->run();

            return $this->serialize($output, null, null);
        } catch (ActionCallException $exception) {
            return $this->serialize(null, $exception->errorCode(), $exception->errorDetails());
        }
    }

    public function getActionsDoc()
    {
        $doc = [];
        foreach ($this->actionClasses as $actionName => $actionClass) {

        }
    }

    /**
     * @param $result
     * @param $errorCode
     * @param $errorDetails
     * @return JsonResponse
     */
    private function serialize($result, $errorCode, $errorDetails)
    {
        $serialized = $this->serializer->serialize(new Output($result, new Error($errorCode, $errorDetails)), 'json');

        $status = $errorCode === null ? JsonResponse::HTTP_OK : JsonResponse::HTTP_BAD_REQUEST;

        return new JsonResponse($serialized, $status, [], true);
    }
}