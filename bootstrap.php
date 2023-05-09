<?php
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

function checkRequirements() {
    $requiredVersion = '8.2.0';
    if (version_compare(PHP_VERSION, $requiredVersion, '<')) {
        throw new \RuntimeException('You need PHP ' . $requiredVersion . 'to run this application');
    }
}

checkRequirements();
