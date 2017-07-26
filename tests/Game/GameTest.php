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

    public function getInvalidGames()
    {
        return [
            [[]],
            [[$this->faker->name]]
        ];
    }
}