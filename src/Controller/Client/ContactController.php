<?php

namespace App\Controller\Client;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/about', name: 'about.index')]
    public function index(): Response
    {
        return $this->render('client/contact/index.html.twig', [
            'controller_name' => 'ContactController',
        ]);
    }
    #[Route('/contact', name: 'contact.index')]
    public function contact(): Response
    {
        return $this->render('client/contact/contact.html.twig', [
            'controller_name' => 'ContactController',
        ]);
    }
}
