<?php
namespace Crimsoncircle\Controller;

// use Crimsoncircle\Model\BlogApi;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BlogApiController
{
    public function index(Request $request): string
    {
        return "SOME";
        if($request->isMethod('get')){

        }
        else{

        }
    }
}