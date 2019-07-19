<?php

namespace store\entities;

interface AggregateRoot
{
    public function releaseEvents(): array;
}