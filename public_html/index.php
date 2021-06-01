<?php

declare(strict_types=1);

use App\Bootstrap\ApplicationBootstrap;

require __DIR__ . '/../vendor/autoload.php';

$applicationBootstrap = new ApplicationBootstrap();
$applicationBootstrap->run();
