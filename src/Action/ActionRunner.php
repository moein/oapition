<?php

namespace Oapition\Action;

class ActionRunner
{
    /**
     * @var callable
     */
    private $action;

    /**
     * @var mixed
     */
    private $request;

    /**
     * ActionRunner constructor.
     * @param callable $action
     * @param mixed $request
     */
    public function __construct(callable $action, $request)
    {
        $this->action = $action;
        $this->request = $request;
    }

    /**
     * @return mixed
     */
    public function run()
    {
        $action = $this->action;

        return $action($this->request);
    }
}