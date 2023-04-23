<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Model\BlogModel;

class BlogController extends AbstractController
{

    public function index(Request $request): JsonResponse
    {
        $blogs = [];
        $msg = '';
        $blogModel = new BlogModel();

        if($request->isMethod('get')){
            $blogs = $this->createBlogsData($blogModel);
            $msg = (sizeof($blogs) > 0 ? 'Watching all blogs' : 'Ups! No Blogs for watch');
        }
        else{
            $msg = ($this->insertBlog($request, $blogModel) ? 'Blog Succesfully Created' : 'Error in given data');
        }

        return new JsonResponse(['message' => $msg, 'data' => $blogs]);
    }//end index function

    private function createBlogsData(BlogModel $blogModel): array {
        $blogs = [];
        $blogsDb = $blogModel->findAllBlogs();
        foreach($blogsDb as $blog) {
            $blogs[] = [
                'created_at' => $blog->getCreatedAt(),
                'id' => $blog->getId(),
                'title' => $blog->getTitle(),
                'content' => $blog->getContent(),
                'author' => $blog->getAuthor(),
                'slug' => $blog->getSlug()
            ];
        }
        return $blogs;
    }//end createBlogsData

    private function insertBlog(Request $request, BlogModel $blogModel): bool {
        $blog = array();
        $blog['title'] = $request->request->get('title');
        $blog['content'] = $request->request->get('content');
        $blog['author'] = $request->request->get('author');
        
        if($blog['title'] == NULL || $blog['content'] == NULL || $blog['author'] == NULL) {
            return FALSE;
        }
        else {
            $blog['slug'] = strtolower(str_replace(' ', '_', $blog['title']));
            $blogModel->insertBlog($blog);
            return TRUE;
        }
    }//end insertBlog


    public function getBlogBySlug(Request $request, EntityManagerInterface $entityManager, string $slugBlog): JsonResponse
    {
        $blog = [];
        $msg = '';
        if($idBlog == NULL){
            $msg = 'The slug of the blog is needed';
            return new JsonResponse(['message' => $msg, 'blog' => $blog]);
        }

        $blog_db = $entityManager->getRepository(Blog::class)->where('deleted_at', NULL)->where('slug', $slugBlog)->find();
        foreach($blog_db as $blog) {
            $blog = [
                'id' => $blog->getId(),
                'title' => $blog->getTitle(),
                'content' => $blog->getContent(),
                'author' => $blog->getAuthor(),
                'slug' => $blog->getSlug()
            ];
        }
        $msg = 'Blog find it';

        if($request->isMethod('get'))
            return new JsonResponse(['message' => $msg, 'blog' => $blog]);
        else {
            $entityManager->remove($blog_db[0]);
            $entityManager->flush();
            return new JsonResponse(['message' => $msg.' and deleted it!', 'blog' => $blog]);
        }
    }      

}
