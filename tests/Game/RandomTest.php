<?php

declare(strict_types=1);

namespace Tests\Game;

use PHPUnit\Framework\TestCase;
use SecretSanta\Game\Random;

class RandomTest extends TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testItThrowsExceptionWhenMinIsBiggerThanMax()
    {
        $random = new Random();
        $random->generate(13, 8);
    }

    /**
     * @dataProvider getRandomIntervals
     * @param $min
     * @param $max
     */
    public function testItGeneratesNumbersInTheRange($min, $max)
    {
        $random = new Random();

        $randNum = $random->generate($min, $max);
        $this->assertLessThanOrEqual($max,$randNum);
        $this->assertGreaterThanOrEqual($min, $randNum);
    }

    public function getRandomIntervals()
    {
        return [
            [0,9],
            [0,0],
            [1,19],
            [-100, 210231]
        ];
    }


}