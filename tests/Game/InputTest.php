<?php

declare(strict_types=1);

namespace Tests\Game;

use PHPUnit\Framework\TestCase;
use SecretSanta\Game\Input;

class InputTest extends TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testItThrowsExceptionOnNonExistingFile()
    {
        $input = new Input('blablabla i do not exist.txt');
        $input->read();
    }

    public function testItReadsAndReturnsProperResults()
    {
        $fixtureFile = __DIR__.'/Fixtures/Input.txt';
        $expectedResult = array('Ana','Bob','Joe');

        $input = new Input($fixtureFile);
        $result = $input->read();

        $this->assertEquals($expectedResult, $result);
    }

    public function testItReadsAndIgnoresWhitespace()
    {
        $fixtureFile = __DIR__.'/Fixtures/InputWhitespace.txt';
        $expectedResult = array('Ana','Bob','Joe');

        $input = new Input($fixtureFile);
        $result = $input->read();

        $this->assertEquals($expectedResult, $result);
    }
}