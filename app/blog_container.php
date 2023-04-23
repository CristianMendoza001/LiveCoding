<?php
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\DsnParser;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM;
use App\Services;

$paths = ['src/Entity'];
$isDevMode = true;
$config = ORMSetup::createAttributeMetadataConfiguration($paths, $isDevMode);

$dsnParser = new DsnParser(['mysql' => 'mysqli', 'postgres' => 'pdo_pgsql']);
$connectionParams = $dsnParser->parse('mysql://root:crimsoncircle@db:3306/blogsite');
$conn = DriverManager::getConnection($connectionParams);
// $sql = "SELECT * FROM blogs";
// dd($conn->query($sql)->fetchAssociative());

 
// init service container 
$containerBuilder = new ContainerBuilder();
 
// add demo service into the service container 
$containerBuilder->register('doctrine.orm.default_entity_manager', ORM\EntityManager::class)
                 ->setArguments([$conn, $config]);
 
// add dependent service into the service container 
$containerBuilder->register('blog.service', Services\BlogService::class)
                 ->addArgument(new Reference('doctrine.orm.default_entity_manager'));
 
// fetch service from the service container 
$blogService = $containerBuilder->get('blog.service');
return $blogService->getEntityManager();