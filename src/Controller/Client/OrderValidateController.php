<?php

namespace App\Controller\Client;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Order;
use App\Entity\Cart;


class OrderValidateController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/order/merci/{stripeSessionId}', name: 'order_validate')]
    public function index($stripeSessionId , Cart $cart)
    {
        $order = $entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        if(!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        if( !$order->getIsPaid() ) {

            $cart->remove();


            $order->setIsPaid(1);
            $this->entityManager->flush();
        }

        return $this->render('client/order/ordersuccess.html.twig', [
            'order' => $order,
        ]);
    }


   
}
