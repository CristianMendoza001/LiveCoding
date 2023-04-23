<?php
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

use Doctrine\DBAL;
use Doctrine\ORM;
use App\Factory;

$paths = ['src/Entity'];
$isDevMode = true;
$config = ORM\ORMSetup::createAttributeMetadataConfiguration($paths, $isDevMode);

$dsnParser = new DBAL\Tools\DsnParser(['mysql' => 'mysqli', 'postgres' => 'pdo_pgsql']);
$connectionParams = $dsnParser->parse('mysql://root:crimsoncircle@db:3306/blogsite');
$conn = DBAL\DriverManager::getConnection($connectionParams);

$containerBuilder = new ContainerBuilder();
$containerBuilder->register('doctrine.orm.default_entity_manager', ORM\EntityManager::class)
                 ->setArguments([$conn, $config]); 
$containerBuilder->register('entity.manager.factory', Factory\EntityManagerFactory::class)
                 ->addArgument(new Reference('doctrine.orm.default_entity_manager'));
                 
$em = $containerBuilder->get('entity.manager.factory');
return $em->getEntityManager();