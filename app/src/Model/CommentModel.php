<?php
namespace App\Model;

use Doctrine\ORM\Tools\Pagination\Paginator;

use App\Entity\Comment;

class CommentModel
{
    
    private $entityManager;
    private $entity;
    private $entitySuffix;
    private $MAX_PAGINATION = 10;

    public function __construct() {
        $this->entityManager = include dirname(__FILE__).'/../../db.php';
        $this->entity = 'App\Entity\Comment';
        $this->entitySuffix = 'c';
    }

    public function findCommentById(int $idComment, ?bool $includeSoftDelete = FALSE): array {
        $query = "SELECT ".$this->entitySuffix." FROM ".$this->entity." ".$this->entitySuffix.
                  ' WHERE '.$this->entitySuffix.'.id = ?1'.
                  ((!$includeSoftDelete) ? ' AND '.$this->entitySuffix.'.deleted_at IS NULL' : '');
        $comment = $this->entityManager->createQuery($query)->setParameter(1, $idComment)->getResult();
        return (sizeof($comment) > 0 ? array($comment[0]) : array());
    }

    public function findCommentsByBlogId(int $idBlog, int $page, ?bool $includeSoftDelete = FALSE): array {
        $firstResult = ($page-1) * $this->MAX_PAGINATION;

        $query = "SELECT ".$this->entitySuffix." FROM ".$this->entity." ".$this->entitySuffix.
                  ' WHERE '.$this->entitySuffix.'.blog_id = ?1'.
                  ((!$includeSoftDelete) ? ' AND '.$this->entitySuffix.'.deleted_at IS NULL' : '');
        $statement = $this->entityManager->createQuery($query)
                                         ->setParameter(1, $idBlog)
                                         ->setFirstResult($firstResult)
                                         ->setMaxResults($this->MAX_PAGINATION); 
        $comments_db = new Paginator($statement, $fetchJoinCollection = true);

        $comments = [];
        foreach($comments_db as $comment){
            $comments[] = $comment;
        }
        return $comments;
    }

    public function insertComment(array $data) {
        $date = new \DateTime();
        
        $comment = new Comment();
        $comment->setCreatedAt($date);
        $comment->setUpdatedAt($date);
        $comment->setBlogId($this->entityManager->getPartialReference('App\Entity\Blog', array('id' => $data['blog_id'])));
        $comment->setContent($data['content']);
        $comment->setAuthor($data['author']);

        $this->entityManager->persist($comment);
        $this->entityManager->flush();
    }

    public function deleteCommentById(int $idComment): bool {
        $query = "SELECT ".$this->entitySuffix." FROM ".$this->entity." ".$this->entitySuffix.
                  ' WHERE '.$this->entitySuffix.'.id = ?1 AND '.$this->entitySuffix.'.deleted_at IS NULL';
        $comment = $this->entityManager->createQuery($query)->setParameter(1, $idComment)->getResult();
        if(sizeof($comment) > 0) {
            $date = new \DateTime();
            $commentToUpdate = $comment[0];
            $commentToUpdate->setDeletedAt($date);

            $this->entityManager->flush();
            return TRUE;
        }
        else 
            return FALSE;
    }

}