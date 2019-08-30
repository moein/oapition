<?php

namespace Oapition\Action\Input;

use Symfony\Component\Security\Core\User\UserInterface;

interface UserAwareInput
{
    public function setUser(UserInterface $user);
}