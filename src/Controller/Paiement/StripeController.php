<?php

namespace App\Controller\Paiement;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Classes\Cart;
use App\Entity\Order;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;



class StripeController extends AbstractController
{


    #[Route('/commande/create-session/{reference}', name: 'commande_create_session')]
    public function index(EntityManagerInterface $entityManager, $reference, Cart $cart)
    {
        $product_for_stripe = [];
        $YOUR_DOMAIN = 'http://localhost:8001';

        $order = $entityManager->getRepository(Order::class)->findOneByReference($reference);

        if (!$order) {
            return new JsonResponse(['error' => 'error']);
        }

        foreach ($order->getOrderDetails()->getValues() as $product) {
        $product_object = $entityManager->getRepository(Product::class)->findOneByName($product->getProduct());
            $product_for_stripe[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $product->getProduct(),
                    ],
                    'unit_amount' => $product->getPrice() * 100, // Stripe requires price in cents                   
                ],
                'quantity' => $product->getQuantity(),
            ];
        }

        $product_for_stripe[] = [
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => $order->getCarrierName(),
                    'images' =>[$YOUR_DOMAIN],
                ],
                'unit_amount' => $order->getCarrierPrice() * 100, // Stripe requires price in cents                   
            ],
            'quantity' => 1,
        ];

        // dd($product_for_stripe);
        Stripe::setApiKey('sk_test_51Ox3m6P9GtTYARbsSbbom39KJQrcCTQY7fNawIQGE4TdhmkdqdVPTT2FwXUXly5H2D5JadrZc2U5YZbeJjCKDXLU00b1sxAPtl');

        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => [ $product_for_stripe],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
        ]);

        $order->setStripeSessionId($checkout_session->id);
        $entityManager->flush();

        return new JsonResponse(['id' => $checkout_session->id]);
        // header("HTTP/1.1 303 See Other");
        // header("Location: " . $checkout_session->url);
       
    }

}
