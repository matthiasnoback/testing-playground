<?php
declare(strict_types=1);

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once __DIR__ . '/../vendor/autoload.php';

// Create a simple "default" Doctrine ORM configuration for Annotations
$isDevMode = true;
\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');
$config = Setup::createAnnotationMetadataConfiguration(
    [__DIR__. '/../src/Domain/Model/PurchaseOrder'],
    $isDevMode,
    null,
    null,
    false
);
// or if you prefer yaml or XML
//$config = Setup::createXMLMetadataConfiguration(array(__DIR__."/config/xml"), $isDevMode);
//$config = Setup::createYAMLMetadataConfiguration(array(__DIR__."/config/yaml"), $isDevMode);

// database configuration parameters
$conn = array(
    'driver' => 'pdo_sqlite',
    'path' => __DIR__ . '/db.sqlite',
);

// obtaining the entity manager
return EntityManager::create($conn, $config);
