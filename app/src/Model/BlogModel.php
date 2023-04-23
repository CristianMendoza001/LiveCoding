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

    public function findBlogBySlug(string $slug, ?bool $includeSoftDelete = FALSE): array {
        $query = "SELECT ".$this->entitySuffix." FROM ".$this->entity." ".$this->entitySuffix.
                  ' WHERE '.$this->entitySuffix.'.slug = ?1'.
                  ((!$includeSoftDelete) ? ' AND '.$this->entitySuffix.'.deleted_at IS NULL' : '');
        $blog = $this->entityManager->createQuery($query)->setParameter(1, $slug)->getResult();
        return (sizeof($blog) > 0 ? array($blog[0]) : array());
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

    public function deleteBlogBySlug(string $slug): bool {
        $query = "SELECT ".$this->entitySuffix." FROM ".$this->entity." ".$this->entitySuffix.
                  ' WHERE '.$this->entitySuffix.'.slug = ?1 AND '.$this->entitySuffix.'.deleted_at IS NULL';
        $blog = $this->entityManager->createQuery($query)->setParameter(1, $slug)->getResult();
        if(sizeof($blog) > 0) {
            $date = new \DateTime();
            $blogToUpdate = $blog[0];
            $blogToUpdate->setDeletedAt($date);

            $this->entityManager->flush();
            return TRUE;
        }
        else 
            return FALSE;
    }

}