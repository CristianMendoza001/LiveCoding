<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Model\CommentModel;

class CommentController extends AbstractController
{
    private $COMMENT_BY_ID = 1;
    private $COMMENTS_BY_BLOG_ID = 2;

    public function index(Request $request, ?int $idComment): JsonResponse
    {
        $comments = [];
        $msg = '';
        $commentModel = new CommentModel();
        
        if($request->isMethod('post')) {
            if($idComment == NULL){
                $comments = $this->insertComment($request, $commentModel);
                $msg = ($comments != NULL ? 'Comment For Blog Succesfully Created' : 'Error in given data');
            }
            else{
                $msg = 'The URL data are incorrect, please try again';
            }
        }

        else if($request->isMethod('get')) {
            if($idComment != NULL) {
                $comments = $this->createCommentsData($commentModel, $idComment, $this->COMMENT_BY_ID);
                $msg = (sizeof($comments) > 0 ? 'Watching the comment of the blog' : "We couldn't find the comment");
            }
            else{
                $msg = 'The URL data are incorrect, please try again';
            }
        }

        else if($request->isMethod('delete')) {
            if($idComment == NULL)
                $msg = 'The id of the comment is required for delete it';
            else {
                $isDeleted = $commentModel->deleteCommentById($idComment);
                $msg = ($isDeleted ? 'Comment Successfully Erased!' : "Doesn't exists the comment for erase!");
            }
        }

        else {
            $msg = "Your petition couldn't be processed";
        }   

        return new JsonResponse(['message' => $msg, 'data' => $comments]);
    }//end index function

    private function createCommentsData(CommentModel $commentModel, int $id, int $kindComments, ?int $page = 0): array {
        $comments = [];
        $commentsDb =  ($kindComments == $this->COMMENT_BY_ID ? $commentModel->findCommentById($id) : $commentModel->findCommentsByBlogId($id, $page));
        foreach($commentsDb as $comment) {
            $comments[] = [
                'created_at' => $comment->getCreatedAt(),
                'id' => $comment->getId(),
                'content' => $comment->getContent(),
                'author' => $comment->getAuthor(),
                'blog_id' => $comment->getBlogId()->getId()
            ];
        }
        return $comments;
    }//end createCommentsData

    private function insertComment(Request $request, CommentModel $commentModel): array {
        $comment = array();
        $comment['blog_id'] = $request->request->get('blog_id');
        $comment['content'] = $request->request->get('content');
        $comment['author'] = $request->request->get('author');
        
        if($comment['blog_id'] == NULL || $comment['content'] == NULL || $comment['author'] == NULL) {
            return array();
        }
        else {
            $commentModel->insertComment($comment);
            return $comment;
        }
    }//end insertComment   

    public function getCommentsByBlog(Request $request, ?int $idBlog) {
        $comments = [];
        $msg = '';
        $commentModel = new CommentModel();
        if($request->isMethod('get')) {
            if($idBlog != NULL) {
                $page = $request->query->get('page');
                if($page == NULL || $page == 0){
                    $msg = 'The pagination is required';
                }
                else{
                    $comments = $this->createCommentsData($commentModel, $idBlog, $this->COMMENTS_BY_BLOG_ID, $page);
                    $msg = (sizeof($comments) > 0 ? 'Watching all the comments of the blog' : "We couldn't find more comments for this blog");
                }
            }
            else{
                $msg = 'The id of the blog is required';
            }
        }
        else {
            $msg = "Your petition couldn't be processed";
        }

        return new JsonResponse(['message' => $msg, 'data' => $comments]);
    }

}
