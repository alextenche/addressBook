<?php

declare(strict_types=1);

/** @var callable(): \Slim\App<\Psr\Container\ContainerInterface> $createApp */
$createApp = require dirname(__DIR__) . '/bootstrap/app.php';
$createApp()->run();
