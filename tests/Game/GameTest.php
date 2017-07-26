<?php

declare(strict_types=1);

namespace Tests\Game;

use PHPUnit\Framework\TestCase;
use SecretSanta\Game\Game;

class GameTest extends TestCase {

    private $faker;

    public function setUp()
    {
        $this->faker = \Faker\Factory::create();
    }

    /**
     * @expectedException \InvalidArgumentException
     * @dataProvider getInvalidGames
     */
    public function testItThrowsErrorWithOneOrLessPlayers($players)
    {
        $game = new Game($players);
        $game->play();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testItThrowsErrorWithNonUniquePlayers()
    {
        $duplicatePlayer = $this->faker->name;
        $players = [
            $this->faker->name,
            $duplicatePlayer,
            $this->faker->name,
            $this->faker->name,
            $duplicatePlayer
        ];

        $game = new Game($players);
        $game->play();
    }

    public function testItWorksWithTwoPlayers()
    {
        $players = array('Bob', 'Ana');
        $game = new Game($players);
        $game->play();

        $result = $game->getResult();
        $this->assertEquals(['Bob' => 'Ana', 'Ana' => 'Bob'], $result);
    }

    public function getInvalidGames()
    {
        return [
            [[]],
            [[$this->faker->name]]
        ];
    }
}