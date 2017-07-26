<?php

namespace SecretSanta\Contracts;

interface Input
{
    /**
     * Read the list of players from a text file
     *
     * @return string[] An array with all player names as strings
     */
    public function read(): array;
}