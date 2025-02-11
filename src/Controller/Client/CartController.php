<?php

namespace App\Controller\Client;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Classes\Cart;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Product;

class CartController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/mon-cart', name: 'app_cart')]
    public function index(Cart $cart)
    {
        
        return $this->render('client/cart/index.html.twig', [
            'cart' => $cart->getFull(),
        ]);
    }

    #[Route('cart/add/{id}', name: 'add_to_cart')]
    public function add(Cart $cart, $id)
    {
        $cart->add($id);
        return $this->redirectToRoute('app_cart');
    }

    #[Route('cart/remove', name: 'remove_my_cart')]
    public function remove(Cart $cart)
    {
        $cart->remove();
        return $this->redirectToRoute('app_shop');
    }
    #[Route('cart/delete/{id}', name: 'delete_to_cart')]
    public function delete(Cart $cart, $id)
    {
        $cart->delete($id);
        return $this->redirectToRoute('app_cart');
    }

    #[Route('cart/decrease/{id}', name: 'decrease_to_cart')]
    public function decrease(Cart $cart, $id)
    {
        $cart->decrease($id);
        return $this->redirectToRoute('app_cart');
    }
}
