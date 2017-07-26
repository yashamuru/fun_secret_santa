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

    private $numPlayers = 0;

    private $blackList = array();

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
        $this->players = array_values($players);
        $this->numPlayers = count($players);
        $this->receivers = array_fill(0, $this->numPlayers, false);
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
        $buyerIdx = 0;
        $lastReceiverIndex = false;
        while ($buyerIdx < $this->numPlayers) {
            try {
                $lastReceiverIndex = $this->move($buyerIdx);
                $this->blackList = array();
            } catch (\LogicException $ex) {
                if (false === $lastReceiverIndex) {
                    throw $ex;
                }

                //Rollback the last step:
                $buyerIdx--;
                $this->receivers[$lastReceiverIndex] = false;
                $this->blackList[$buyerIdx][] = $lastReceiverIndex;
                $lastReceiverIndex = false;
                continue;
            }
            $buyerIdx++;
        }
    }

    public function move(int $buyerIdx)
    {
        $availableReceivers = $this->getAvailableReceivers($buyerIdx);
        if (empty($availableReceivers)) {
            throw new \LogicException('Game crashed - cannot complete the game');
        }
        $receiverIdx = $this->getRandomElement($availableReceivers);

        $this->receivers[$receiverIdx] = $buyerIdx;

        $receiverName = $this->players[$receiverIdx];
        $playerName = $this->players[$buyerIdx];
        $this->result[$playerName] = $receiverName;
        return $receiverIdx;
    }

    public function getAvailableReceivers(int $buyerIdx): array
    {
        $availableReceivers = array();
        foreach ($this->receivers as $receiverIdx => $value) {
            if (false === $value
                && $buyerIdx != $receiverIdx
                && !in_array($receiverIdx, $this->blackList[$buyerIdx] ?? [])
            ) {
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