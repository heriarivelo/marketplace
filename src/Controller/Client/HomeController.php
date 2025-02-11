<?php

namespace App\Controller\Client;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Product;

class HomeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/client/home', name: 'app_client_home')]
    public function index()
    {
        $product = $this->entityManager->getRepository(Product::class)->findByIsBest(1);
        // dd($product);
        return $this->render('client/home/index.html.twig', [
            'bestproduct' => $product,
        ]);
    }
}
