<?php 
namespace App\Factory;

use Doctrine\ORM\EntityManager;

class EntityManagerFactory
{
    private $entityManager;

    public function __construct(EntityManager $entityManager){
        $this->entityManager = $entityManager;
    }

    public function getEntityManager() {
        return $this->entityManager;
    }

}