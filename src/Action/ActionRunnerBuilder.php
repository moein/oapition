<?php

namespace Oapition\Action;

use Oapition\Action\Exception\ActionCallException;
use Oapition\Action\Input\HttpAwareInput;
use Oapition\Action\Input\InputBuilder;
use Oapition\Action\Exception\ActionClassNotFound;
use Oapition\Action\Exception\ActionNotCallableObject;
use Oapition\Action\Input\UserAwareInput;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class ActionRunnerBuilder
{
    /**
     * @var array
     */
    private $actions = [];

    /**
     * @var InputBuilder
     */
    private $inputBuilder;

    /**
     * @param InputBuilder $inputBuilder
     */
    public function __construct(InputBuilder $inputBuilder)
    {
        $this->inputBuilder = $inputBuilder;
    }

    /**
     * @param $action
     */
    public function addAction($action)
    {
        if (!is_callable($action) || !is_object($action)) {
            throw new ActionNotCallableObject(gettype($action));
        }

        $this->actions[get_class($action)] = $action;
    }

    /**
     * @param string $actionClass
     * @param array $payload
     * @param Request $httpRequest
     * @param null|UserInterface $user
     * @return ActionRunner
     */
    public function build(string $actionClass, array $payload, Request $httpRequest, ?UserInterface $user)
    {
        $action = $this->actions[$actionClass] ?? null;
        if ($action === null) {
            throw new ActionClassNotFound($actionClass);
        }
        $input = $this->inputBuilder->build($action, $payload);
        if ($input instanceof UserAwareInput) {
            if ($user === null) {
                throw new ActionCallException('auth_required', ['User authentication for this action is required']);
            }
            $input->setUser($user);
        }
        if ($input instanceof HttpAwareInput) {
            $input->setRequest($httpRequest);
        }

        return new ActionRunner($action, $input);
    }
}