<?php

namespace App\Controller\Client;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Classes\Filtre;
use App\Entity\Product;
use App\Form\FiltreType;
use Symfony\Component\HttpFoundation\Request;

class ShopController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/shop', name: 'app_shop')]
    public function index(Request $request): Response
    {
        $product = $this->entityManager->getRepository(Product::class)->findAll();
        $search = new Filtre();
        $form = $this->createForm(FiltreType::class, $search);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            // $search = $form->getData();
            $product = $this->entityManager->getRepository(Product::class)->findWithSearch($search);

        }
        return $this->render('client/shop/index.html.twig', [
            'products' => $product,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/product/{slug}', name: 'app_product_detail')]
    public function productDetail($slug): Response
    {
        $product = $this->entityManager->getRepository(Product::class)->findOneBySlug($slug);
        if (!$product) {
            return $this->redirectToRoute('app_shop');
        }
        return $this->render('client/shop/product_detail.html.twig', [
            'product' => $product,
        ]);
    }
    // #[Route('/produit/{slug}', name: 'product')]
    // public function show($slug): Response
    // {
    //     $product = $this->entityManager->getRepository(Product::class)->findOneBySlug($slug);
    //     return $this->render('admin/product/index.html.twig', [
    //         'product' => $product,
    //     ]);
    // }
    // #[Route('/cart', name: 'app_cart')]
    // public function cart(): Response
    // {
    //     return $this->render('client/shop/cart.html.twig', [
    //         'controller_name' => 'ShopController',
    //     ]);
    // }
}
