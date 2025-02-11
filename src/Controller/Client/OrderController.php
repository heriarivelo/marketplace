<?php

namespace App\Controller\Client;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\OrderType;
use Symfony\Component\HttpFoundation\Request;
use App\Classes\Cart;
use App\Entity\Order;
use App\Entity\OrderDetails;
// use Symfony\Component\Validator\Constraints\DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class OrderController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/order', name: 'app_order')]
    public function index(Cart $cart)
    {

        if(!$this->getUser()->getAddresses()->getValues())
        {
            return $this->redirectToRoute('app_add_address');
        }

        $form = $this->createForm(OrderType::class, null,[
            'user' => $this->getUser()
        ]);
        return $this->render('client/order/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart->getFull()
        ]);
    }

    #[Route('/order/recapitulatif', name: 'app_order_recap', methods: ["POST"])]
    public function add(Cart $cart, Request $request)
    {
        $form = $this->createForm(OrderType::class, null,[
            'user' => $this->getUser()
        ]);
        $form->handleRequest($request);
        // dd($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $date = new DateTimeImmutable();
            $carriers = $form->get('carriers')->getData();
            $delivery = $form->get('adresses')->getData();
            $delivery_content = $delivery->getFirstname().' '.$delivery->getLastname();
            $delivery_content .= '<br/>'.$delivery->getPhone();

            if ($delivery->getCompany())
            {
                 $delivery_content .= '<br/>'.$delivery->getCompany();
            }
            $delivery_content .= '<br/>'.$delivery->getAddress();
            $delivery_content .= '<br/>'.$delivery->getPostal().' '.$delivery->getCity();
            $delivery_content .= '<br/>'.$delivery->getCountry();

            $order = new Order();
            $reference = $date->format('dmY').'-'.uniqid();
            $order->setReference($reference);
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCarrierName($carriers->getName());
            $order->setCarrierPrice($carriers->getPrice());
            $order->setDelivery($delivery_content);
            $order->setIsPaid(0);

            $this->entityManager->persist($order);
            

            foreach ($cart->getFull() as $product) {
                $orderDetails = new OrderDetails();
                $orderDetails->setMyorder($order);
                $orderDetails->setProduct($product['product']->getName());
                $orderDetails->setQuantity($product['quantity']);
                $orderDetails->setPrice($product['product']->getPrix());
                $orderDetails->setTotal($product['product']->getPrix() * $product['quantity'] );

                $this->entityManager->persist($orderDetails);

                // dd($product);
            }

            $this->entityManager->flush();
            
            return $this->render('client/order/add.html.twig', [
                'cart' => $cart->getFull(),
                'carrier' => $carriers,
                'delivery' => $delivery_content,
                'reference' => $order->getReference()

            ]);
        }
        return $this->redirectToRoute('app_cart');
        
    }
}
