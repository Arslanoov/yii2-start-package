<?php

namespace store\dispatchers;

interface EventDispatcher
{
    public function dispatch($event): void;
    public function dispatchAll(array $events): void;
}