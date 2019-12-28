<?php

namespace Oapition\Action\Input;

use Symfony\Component\Security\Core\User\UserInterface;

interface UserAwareInput
{
    /**
     * @param UserInterface $user
     */
    public function setUser(UserInterface $user);
}