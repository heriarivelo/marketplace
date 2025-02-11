<?php

namespace App\Controller\Client;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'app_blog')]
    public function index(): Response
    {
        return $this->render('client/blog/index.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }
    #[Route('/blog/detail', name: 'app_blog_detail')]
    public function detailblog(): Response
    {
        return $this->render('client/blog/blogdetail.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }
}
