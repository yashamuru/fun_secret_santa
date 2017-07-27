<?php

declare(strict_types=1);

namespace SecretSanta\Game;

use SecretSanta\Contracts;

class Random implements Contracts\Random
{
    public function generate(int $min, int $max): int
    {
        if ($min > $max) {
            $msg = sprintf('Random - Min(%s) should not be bigger than Max(%s)',$min, $max);
            throw new \InvalidArgumentException($msg);
        }

        return mt_rand($min, $max);
    }
}