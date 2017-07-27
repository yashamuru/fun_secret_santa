<?php

declare(strict_types=1);

namespace SecretSanta\Game;

use SecretSanta\Contracts;

class Output implements Contracts\Output
{
    public function printLine(string $line)
    {
        echo $line.PHP_EOL;
    }
}