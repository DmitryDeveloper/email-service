<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class BlogController extends AbstractController
{
    //regular expression or Requirement class
    #[Route('/blog/{page}', name: 'blog_list', requirements: ['page' => Requirement::DIGITS])]
    public function list(int $page = 1): Response
    {
        // ...
    }

    #[Route('/blog/{slug}', name: 'blog_show')]
    public function show($slug): Response
    {
        // ...
    }
}