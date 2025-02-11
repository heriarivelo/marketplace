<?php

namespace App\Controller\Client;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Address;
use App\Form\AddressType;
use App\Classes\Cart;
use Doctrine\ORM\EntityManagerInterface;

class AccountAddressController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/account/address', name: 'app_address')]
    public function index(Request $request)
    {
        // $address = new Address();

        // $form = $this->createForm(AddressType::class, $address);
        
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        //     $address->setUser($this->getUser());
        //     // dd($address);
        //     $entityManager = $this->getDoctrine()->getManager();
        //     $entityManager->persist($address);
        //     $entityManager->flush();

        //     return $this->redirectToRoute('app_address');
        // }
        return $this->render('client/account/address.html.twig' /*, [
            'form' => $form->createView(),
        ]*/);
    }

    #[Route('/account/add-address', name: 'app_add_address', methods: ['GET', 'POST'])]
    public function add(Cart $cart, Request $request): Response
    {
        $address = new Address();

        $form = $this->createForm(AddressType::class, $address);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address->setUser($this->getUser());
            // dd($address);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($address);
            $entityManager->flush();
            if( $cart->get())
            {
                return $this->redirectToRoute('app_order');  
            } else {
            return $this->redirectToRoute('app_address');
            }
        }
        
        return $this->render('client/account/add-address.html.twig', [
            'form' => $form->createView(),
        ]);


    }

    #[Route('/account/edit-address/{id}', name: 'edit_address', methods: ['GET', 'POST'])]
    public function edit(Request $request, $id): Response
    {

        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);

        if ( !$address || $address->getUser() != $this->getUser() ) {
            return $this->redirectToRoute('app_address');
        }
        $form = $this->createForm(AddressType::class, $address);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('app_address');
        }
        
        return $this->render('client/account/add-address.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/account/delete-address/{id}', name: 'delete_address', methods: ['GET', 'POST'])]
    public function delete($id): Response
    {

        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);

        if ( $address && $address->getUser() == $this->getUser() ) {
            $this->entityManager->remove($address);
            $this->entityManager->flush();
            
        }
            return $this->redirectToRoute('app_address');
    }
}
