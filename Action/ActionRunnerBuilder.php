<?php

namespace Oapition\Action;

use Oapition\Action\Exception\ActionCallException;
use Oapition\Action\Input\InputBuilder;
use Oapition\Action\Exception\ActionClassNotFound;
use Oapition\Action\Exception\ActionNotCallableObject;
use Oapition\Action\Input\UserAwareInput;
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
    private $requestBuilder;

    /**
     * @param InputBuilder $requestBuilder
     */
    public function __construct(InputBuilder $requestBuilder)
    {
        $this->requestBuilder = $requestBuilder;
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
     * @param null|UserInterface $user
     * @return ActionRunner
     */
    public function build(string $actionClass, array $payload, ?UserInterface $user)
    {
        $action = $this->actions[$actionClass] ?? null;
        if ($action === null) {
            throw new ActionClassNotFound($actionClass);
        }
        $request = $this->requestBuilder->build($action, $payload);
        if ($request instanceof UserAwareInput) {
            if ($user === null) {
                throw new ActionCallException('auth_required', ['User authentication for this action is required']);
            }
            $request->setUser($user);
        }

        return new ActionRunner($action, $request);
    }
}