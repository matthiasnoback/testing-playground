<?php
declare(strict_types=1);

use Doctrine\ORM\Tools\Console\ConsoleRunner;

$entityManager = require __DIR__ . '/bootstrap.php';

return ConsoleRunner::createHelperSet($entityManager);
