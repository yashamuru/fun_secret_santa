<?php

declare(strict_types=1);

namespace Tests\Game;

use PHPUnit\Framework\TestCase;
use SecretSanta\Game\Output;

class OutputTest extends TestCase
{
    /**
     * @dataProvider  getValidInputItems
     */
    public function testItOutputsAsExpected($items, $expectedOutput)
    {
        $string = "Some text";
        ob_start();
        $output = new Output();
        $output->printLine($string);
        $outputResult = ob_get_clean();
        $this->assertEquals($string . PHP_EOL, $outputResult);
    }
}