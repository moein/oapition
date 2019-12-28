<?php

namespace Oapition\Action\Input;

use Symfony\Component\HttpFoundation\Request;

interface HttpAwareInput
{
    /**
     * @param Request $request
     */
    public function setRequest(Request $request);
}