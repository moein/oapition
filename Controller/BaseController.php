<?php

namespace Oapition\Controller;

use Oapition\Handler\JsonHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class BaseController
{
    /**
     * @var JsonHandler
     */
    private $jsonHandler;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @param JsonHandler $jsonHandler
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(JsonHandler $jsonHandler, TokenStorageInterface $tokenStorage)
    {
        $jsonHandler->addActions($this->getActions());
        $this->jsonHandler = $jsonHandler;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function call(Request $request)
    {
        $user = $this->tokenStorage->getToken() ? $this->tokenStorage->getToken()->getUser() : null;
        if (!$user instanceof UserInterface) {
            $user = null;
        }

        return $this->jsonHandler->handle($request, $user);
    }

    /**
     * @return JsonResponse
     */
    public function doc()
    {

    }

    abstract protected function getActions(): array;
}