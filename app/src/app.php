<?php
use Symfony\Component\Routing;

$routes = new Routing\RouteCollection();
$routes->add('leap_year', new Routing\Route('/is_leap_year/{year}', [
    'year' => null,
    '_controller' => 'Crimsoncircle\Controller\LeapYearController::index',
]));
$routes->add('blog', new Routing\Route('/blog', [
    '_controller' => 'Crimsoncircle\Controller\BlogApiController::index',
]));

return $routes;

