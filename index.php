<?php

declare(strict_types=1);

require './vendor/autoload.php';

use SecretSanta\Game;

$filename = __DIR__ . '/players.txt';
if (!empty($argv[1])) {
    $filename = $argv[1];
}

$input = new Game\Input($filename);
$output = new Game\Output();
$random = new Game\Random();

$game = new Game\Game($input, $output, $random);
$game->play();
$game->output();