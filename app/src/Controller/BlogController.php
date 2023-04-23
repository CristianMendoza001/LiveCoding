<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

use App\Entity\Blog;

class BlogController extends AbstractController
{

    public function index(Request $request): JsonResponse
    {
        $blogs = [];
        $msg = '';
        $date = new \DateTime();
        $entityManager = include dirname(__FILE__).'/../../db.php';
        dd($entityManager->find('App\Entity\Blog', 1));

        if($request->isMethod('get')){
            $blogs_db = $entityManager->getRepository(Blog::class)->where('deleted_at', NULL)->findAll();
            foreach($blogs_db as $blog) {
                $blogs[] = [
                    'id' => $blog->getId(),
                    'title' => $blog->getTitle(),
                    'content' => $blog->getContent(),
                    'author' => $blog->getAuthor(),
                    'slug' => $blog->getSlug()
                ];
            }
            $msg = 'Watching all blogs';
        }//end if it's a get
        else{
            $title = $request->request->get('title');
            $content = $request->request->get('content');
            $author = $request->request->get('author');
            
            if($title == NULL || $content == NULL || $author == NULL){
                $msg = 'Error in given data';
            }
            else {
                $slug = strtolower(str_replace(' ', '_', $title));
                $blog = new Blog();
                $blog->setCreatedAt($date);
                $blog->setUpdatedAt($date);
                $blog->setTitle($title);
                $blog->setContent($content);
                $blog->setAuthor($author);
                $blog->setSlug($slug);
                
                $entityManager->persist($blog);
                $entityManager->flush();

                $msg = 'Blog created Succesfully';
            }
        }//end else it's a get
        return new JsonResponse(['message' => $msg, 'data' => $blogs]);
    }

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
