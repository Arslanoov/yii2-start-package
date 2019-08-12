<?php

namespace core\entities;

interface AggregateRoot
{
    public function releaseEvents(): array;
}