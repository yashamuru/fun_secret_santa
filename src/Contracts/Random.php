<?php

namespace SecretSanta\Contracts;

interface Random
{
    public function generate(int $min, int $max): int;
}