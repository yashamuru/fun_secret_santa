<?php

declare(strict_types=1);

namespace SecretSanta\Game;

use SecretSanta\Contracts;

class Input implements Contracts\Input
{
    /**
     * @var string
     */
    private $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function read(): array
    {
        $res = array();
        if (!file_exists($this->filename)) {
            throw new \InvalidArgumentException("Input -> File does not exist");
        }

        $fh = fopen($this->filename, 'r');
        while($line = fgets($fh)) {
            $item = trim($line);
            if ($item) {
                $res[] = $item;
            }
        }

        return $res;
    }

}