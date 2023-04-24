<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Model\BlogModel;

class BlogController extends AbstractController
{

    public function index(Request $request, ?string $slug): JsonResponse
    {
        $blogs = [];
        $msg = '';
        $blogModel = new BlogModel();

        if($request->isMethod('get')){
            $blogs = ($slug == NULL ? $this->createBlogsData($blogModel) : $this->createBlogsData($blogModel, $slug));
            $msg = (sizeof($blogs) > 0 ? (($slug == NULL) ? 'Watching all blogs' : 'Watching the selected blog') 
                                       : (($slug == NULL) ? 'Ups! No Blogs for watch' : 'Ups! No blog with that slug'));
        }

        else if($request->isMethod('post')) {
            if($slug == NULL){
                $blogs = $this->insertBlog($request, $blogModel);
                $msg = ($blogs != NULL ? 'Blog Succesfully Created' : 'Error in given data');
            }
            else{
                $msg = 'The URL data are incorrect, please try again';
            }
        }

        else if($request->isMethod('delete')) {
            if($slug == NULL)
                $msg = 'The slug is required for delete the blog';
            else {
                $isDeleted = $blogModel->deleteBlogBySlug($slug);
                $msg = ($isDeleted ? 'Blog Successfully Erased!' : "Doesn't exists the blog for erase!");
            }
        }
        
        else {
            $msg = "Your petition couldn't be processed";
        }

        return new JsonResponse(['message' => $msg, 'data' => $blogs]);
    }//end index function

    private function createBlogsData(BlogModel $blogModel, ?String $slug = ''): array {
        $blogs = [];
        $blogsDb = ($slug == NULL ? $blogModel->findAllBlogs() : $blogModel->findBlogBySlug($slug));
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

    private function insertBlog(Request $request, BlogModel $blogModel): array {
        $blog = array();
        $blog['title'] = $request->request->get('title');
        $blog['content'] = $request->request->get('content');
        $blog['author'] = $request->request->get('author');
        
        if($blog['title'] == NULL || $blog['content'] == NULL || $blog['author'] == NULL) {
            return array();
        }
        else {
            $blog['slug'] = strtolower(str_replace(' ', '_', $blog['title']));
            $blogModel->insertBlog($blog);
            return $blog;
        }
    }//end insertBlog   

}
