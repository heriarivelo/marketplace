<?php

namespace App\Controller\Client;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'app__account')]
    public function index(): Response
    {
        return $this->render('client/account/index.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }
}
