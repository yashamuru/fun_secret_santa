<?php

declare(strict_types=1);

namespace SecretSanta\Game;

use SecretSanta\Contracts;

class Game implements Contracts\Game
{

    /**
     * @var Contracts\Input
     */
    private $input;

    /**
     * @var Contracts\Output
     */
    private $output;

    /**
     * @var Contracts\Random
     */
    private $random;

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

    /**
     * Game constructor.
     * @param Contracts\Input $input
     * @param Contracts\Output $output
     * @param Contracts\Random $random
     */
    public function __construct(Contracts\Input $input, Contracts\Output $output, Contracts\Random $random)
    {
        $this->input = $input;
        $this->output = $output;
        $this->random = $random;

        $this->initialize();
    }

    private function initialize()
    {
        $players = $this->input->read();

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
        $receiverIdx = -1;
        while ($buyerIdx < $this->numPlayers) {
            try {
                $receiverIdx = $this->move($buyerIdx);
                $this->blackList = array();
                $this->applyMove($buyerIdx, $receiverIdx);

            } catch (\LogicException $ex) {
                if (-1 == $receiverIdx) {
                    throw $ex;
                }

                //Rollback the last step:
                $buyerIdx--;
                $this->rollbackMove($buyerIdx, $receiverIdx);
                $receiverIdx = -1;
                continue;
            }
            $buyerIdx++;
        }
    }

    private function rollbackMove(int $buyerIdx, int $lastReceiverIndex)
    {
        $this->receivers[$lastReceiverIndex] = false;

        $buyerName = $this->players[$buyerIdx];
        unset($this->result[$buyerName]);

        $this->blackList[$buyerIdx][] = $lastReceiverIndex;
    }

    private function applyMove(int $buyerIdx, int $receiverIdx)
    {
        $this->receivers[$receiverIdx] = $buyerIdx;

        $receiverName = $this->players[$receiverIdx];
        $buyerName = $this->players[$buyerIdx];
        $this->result[$buyerName] = $receiverName;
    }

    private function move(int $buyerIdx)
    {
        $availableReceivers = $this->getAvailableReceivers($buyerIdx);
        if (empty($availableReceivers)) {
            throw new \LogicException('Game crashed - cannot complete the game');
        }
        $receiverIdx = $this->getRandomElement($availableReceivers);


        return $receiverIdx;
    }

    private function getAvailableReceivers(int $buyerIdx): array
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

    private function getRandomElement($array)
    {
        $idx = $this->random->generate(0, count($array) -1);
        return $array[$idx];
    }

    public function getResult(): array
    {
        return $this->result;
    }

    public function output(): void
    {
        foreach ($this->result as $buyer => $receiver) {
            $line = sprintf("%s -> %s", $buyer, $receiver);
            $this->output->printLine($line);
        }
    }
}