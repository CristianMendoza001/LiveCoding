<?php 
namespace App\Services;

use Doctrine\ORM\EntityManager;
use App\Entity\Pruebita;

class BlogService
{
    private $entityManager;

    public function __construct(EntityManager $entityManager){
        $this->entityManager = $entityManager;
    }

    public function getEntityManager() {
        $SQL = "SELECT id FROM App\Entity\Pruebita";
        // $qb = $this->entityManager->createQueryBuilder();
        // $some = $qb->select()->from('App\Entity\Blogs', 'b')->getQuery()->getResult();
        // dd($some);
        dd($this->entityManager->find('App\Entity\Blogs', 1));
        // $pruebita = new Pruebita();
        // $pruebita->setTexto('bdshgbfdsgfhdfbh');

        // $this->entityManager->persist($pruebita);
        dd($this->entityManager->flush());
        return $this->entityManager;
    }

}