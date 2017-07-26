<?php

declare(strict_types=1);

namespace Tests\Game;

use PHPUnit\Framework\TestCase;
use SecretSanta\Game\Game;

class GameTest extends TestCase
{

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

    /**
     * @dataProvider getFirstElementPseudoRandomData
     */
    public function testItWorksWhenRandomReturnsFirstElement(array $players, array $expectedResult)
    {
        $game = new Game($players);
        $game->play();

        $result = $game->getResult();
        $this->assertEquals($expectedResult, $result);
    }

    public function getFirstElementPseudoRandomData()
    {
        return [
            [['Bob', 'Ana'], ['Bob' => 'Ana', 'Ana' => 'Bob']],
            [['1', '2', '3'], ['1' => '2', '2' => '3', '3' => '1']]
        ];
    }

    public function testItThrowsExceptionWIthThreeElementsAndFirstIndex()
    {
        $this->markTestSkipped('Case fixed - added in the prev test.');
        $this->expectException(\LogicException::class);
        $players = ['1', '2', '3'];
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