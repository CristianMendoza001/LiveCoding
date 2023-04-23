<?php
namespace App\Model;

use App\Entity\Blog;

class BlogModel
{
    
    private $entityManager;
    private $entity;
    private $entitySuffix;

    public function __construct() {
        $this->entityManager = include dirname(__FILE__).'/../../db.php';
        $this->entity = 'App\Entity\Blog';
        $this->entitySuffix = 'b';
    }

    public function findAllBlogs(?bool $includeSoftDelete = FALSE): array {
        $query = "SELECT ".$this->entitySuffix." FROM ".$this->entity." ".$this->entitySuffix.
                  ((!$includeSoftDelete) ? ' WHERE '.$this->entitySuffix.'.deleted_at IS NULL' : '');
        $blogs = $this->entityManager->createQuery($query)->getResult();
        return $blogs;
    }

    public function insertBlog(array $data) {
        $date = new \DateTime();

        $blog = new Blog();
        $blog->setCreatedAt($date);
        $blog->setUpdatedAt($date);
        $blog->setTitle($data['title']);
        $blog->setContent($data['content']);
        $blog->setAuthor($data['author']);
        $blog->setSlug($data['slug']);

        $this->entityManager->persist($blog);
        $this->entityManager->flush();
    }

}