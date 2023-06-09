<?php
use Symfony\Component\Routing;

$routes = new Routing\RouteCollection();
$routes->add('leap_year', new Routing\Route('/is_leap_year/{year}', [
    'year' => null,
    '_controller' => 'App\Controller\LeapYearController::index',
]));

//TODO: Delete these routes for testing only
$routes->add('product', new Routing\Route('/product', [
    '_controller' => 'App\Controller\ProductController::index',
]));
$routes->add('create_product', new Routing\Route('/create_product', [
    '_controller' => 'App\Controller\ProductController::createProduct',
]));

//Blog section
$routes->add('blog', new Routing\Route('/blog/{slug}', [
    'slug' => null,
    '_controller' => 'App\Controller\BlogController::index',
]));

//Comment section
$routes->add('comments_blog', new Routing\Route('/comment/post/{idBlog}', [
    'idBlog' => null,
    '_controller' => 'App\Controller\CommentController::getCommentsByBlog',
]));
$routes->add('comment', new Routing\Route('/comment/{idComment}', [
    'idComment' => null,
    '_controller' => 'App\Controller\CommentController::index',
]));

return $routes;

