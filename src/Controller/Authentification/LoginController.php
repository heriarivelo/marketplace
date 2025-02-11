<?php

namespace App\Controller\Authentification;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\RegisterType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class LoginController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // #[Route('/login', name: 'login')]
    // public function index(): Response
    // {
    //     return $this->render('authentification/login/index.html.twig', [
    //         'controller_name' => 'LoginController',
    //     ]);
    // }
    #[Route('/register', name: 'app_register')]
    public function register(Request $request , UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form= $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_login');
        }
        return $this->render('authentification/login/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/resetpassword', name: 'app__login')]
    public function resetpassword(): Response
    {
        return $this->render('authentification/login/resetpassword.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }
}

