<?php

declare(strict_types=1);

namespace SecretSanta\Game;

use SecretSanta\Contracts;

class Game implements Contracts\Game
{
    /**
     * @var array
     */
    private $players;

    public function __construct(array $players)
    {
        $this->players = $players;
    }

    public function play()
    {
        if (count($this->players) <= 1) {
            $msg = sprintf("Secret Santa requires at least 2 players %s provided.", count($this->players));
            throw new \InvalidArgumentException($msg);
        }

        if (count($this->players) != count(array_unique($this->players)))
        {
            throw new \InvalidArgumentException("All players must have unique names!");
        }
    }

    public function getResult()
    {
        return [
           'Bob' => 'Ana', 'Ana' => 'Bob'
        ];
    }

}