<?php

namespace App\Commands\Contracts;

interface CommandInterface
{
    public function execute(): mixed;
}
