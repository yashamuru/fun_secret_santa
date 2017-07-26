<?php

declare(strict_types=1);

namespace SecretSanta\Game;

use SecretSanta\Contracts;

class Game implements Contracts\Game
{
    /**
     * @var array
     */
    private $players = array();

    /**
     * @var array
     */
    private $receivers = array();

    /**
     * @var array
     */
    private $result = array();

    public function __construct(array $players)
    {
        $this->players = $players;

        foreach (array_keys($this->players) as $playerIdx) {
            $this->receivers[$playerIdx] = false;
        }
    }

    public function play()
    {
        if (count($this->players) <= 1) {
            $msg = sprintf("Secret Santa requires at least 2 players %s provided.", count($this->players));
            throw new \InvalidArgumentException($msg);
        }

        if (count($this->players) != count(array_unique($this->players))) {
            throw new \InvalidArgumentException("All players must have unique names!");
        }

        //Basic play:
        foreach ($this->players as $buyerIdx => $playerName) {
            $availableReceivers = $this->getAvailableReceivers($buyerIdx);
            if (empty($availableReceivers)) {
                throw new \LogicException('Game crashed - cannot complete the game');
            }
            $receiverIdx = $this->getRandomElement($availableReceivers);

            $this->receivers[$receiverIdx] = $buyerIdx;

            $receiverName = $this->players[$receiverIdx];
            $this->result[$playerName] = $receiverName;
        }
    }

    public function getAvailableReceivers(int $buyerIdx): array
    {
        $availableReceivers = array();
        foreach ($this->receivers as $receiverIdx => $value) {
            if (false === $value && $buyerIdx != $receiverIdx) {
                $availableReceivers[] = $receiverIdx;
            }
        }
        return $availableReceivers;
    }

    public function getRandomElement($array)
    {
        //ToDo - for now.
        return $array[0];
    }

    public function getResult(): array
    {
        return $this->result;
    }

}