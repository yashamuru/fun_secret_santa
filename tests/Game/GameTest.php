<?php

declare(strict_types=1);

namespace Tests\Game;

use PHPUnit\Framework\TestCase;
use SecretSanta\Game;
use SecretSanta\Contracts;
use Prophecy\Argument;

class GameTest extends TestCase
{

    private $faker;

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

    public function setUp()
    {
        $this->faker = \Faker\Factory::create();
        $this->input = $this->prophesize(Contracts\Input::class);
        $this->output = new Game\Output();
        $this->random = $this->prophesize(Contracts\Random::class);
    }

    private function getSut(array $players): Game\Game
    {
        $this->input->read()->willReturn($players);
        return new Game\Game($this->input->reveal(), $this->output, $this->random->reveal());
    }
    /**
     * @expectedException \InvalidArgumentException
     * @dataProvider getInvalidGames
     */
    public function testItThrowsErrorWithOneOrLessPlayers($players)
    {
        $this->getSut($players)->play();
    }

    public function getInvalidGames()
    {
        return [
            [[]],
            [['Bob']]
        ];
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

        $this->getSut($players)->play();
    }

    /**
     * @dataProvider getFirstElementPseudoRandomData
     */
    public function testItWorksWhenRandomReturnsFirstElement(array $players, array $expectedResult)
    {
        $this->random->generate(Argument::type('integer'), Argument::type('integer'))->willReturnArgument(0);
        $game = $this->getSut($players);
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

    public function testItOutputsCorrectly()
    {
        $this->output = new Game\Output();
        $this->random->generate(Argument::type('integer'), Argument::type('integer'))->willReturnArgument(0);
        $players = ['Bob','Ana'];
        $game = $this->getSut($players);

        ob_start();
        $game->play();
        $game->output();
        $outputResult = ob_get_clean();

        $expectedOutput = "Bob -> Ana".PHP_EOL."Ana -> Bob".PHP_EOL;
        $this->assertEquals($expectedOutput, $outputResult);
    }
}