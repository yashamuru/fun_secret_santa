<?php

declare(strict_types=1);

namespace Tests\Game;

use PHPUnit\Framework\TestCase;
use SecretSanta\Game\Output;

class OutputTest extends TestCase
{
    public function testItOutputsAsExpected()
    {
        $string = "Some text";
        ob_start();
        $output = new Output();
        $output->printLine($string);
        $outputResult = ob_get_clean();
        $this->assertEquals($string . PHP_EOL, $outputResult);
    }
}