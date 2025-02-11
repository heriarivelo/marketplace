<?php

namespace App\Controller\Client;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Order;

class AccountOrderController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/account/order', name: 'account_order')]
    public function index(): Response
    {

        return $this->render('client/account/myorder.html.twig', [
            'controller_name' => 'AccountOrderController',
        ]);
    }

    #[Route('/account/order/succes', name: 'account_ordersuccess')]
    public function success(): Response
    {

        $orders = $entityManager->getRepository(Order::class)->findSuccessOrder($this->getUser());


        return $this->render('client/account/myorder.html.twig', [
            'orders' =>  $orders ,
        ]);
    }

    #[Route('/account/order/{reference}', name: 'account_ordershow')]
    public function show($reference)
    {

        $order = $entityManager->getRepository(Order::class)->findOneByReference($reference);

        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('account_order');
        }


        return $this->render('client/account/myorder_show.html.twig', [
            'order' =>  $order ,
        ]);
    }
}
