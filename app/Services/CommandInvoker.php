<?php

namespace App\Services;

use App\Commands\Contracts\CommandInterface;

class CommandInvoker
{
    public function execute(CommandInterface $command): mixed
    {
        return $command->execute();
    }
}