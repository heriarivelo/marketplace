<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;

class ProductController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/admin/product', name: 'app_admin_product')]
    public function index(Request $request): Response
    {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('illustration')->getData();
    
            // Vérifier si un fichier a été téléchargé
            if ($file) {
                // Déplacer le fichier téléchargé vers le répertoire souhaité
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                $file->move($this->getParameter('images_directory'), $fileName);
                
                // Mettre à jour l'entité Product avec le nom du fichier
                $product->setIllustration('assets/images/shop/product/' . $fileName);
            }
            $product->setIsBest(1);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_admin_product');
        }
        $product = $this->entityManager->getRepository(Product::class)->findAll();
       
        return $this->render('admin/product/index.html.twig', [
            'form' => $form->createView(),
            'products' => $product,
        ]);
    }

    #[Route("/product/new", name: "product_new", methods: ["POST"])]

    public function new(Request $request): Response
    {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            dd($form);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            // $imageFile = $product->getIllustrationFile();
            dd($imageFile);
                $fileName = md5(uniqid()) . '.' . $imageFile->guessExtension();
                $product->setIllustration($fileName);
            $entityManager->flush();
    
          
            return $this->redirectToRoute('app_admin_product');
        }
    
        // Passer la variable 'form' au template Twig pour afficher le formulaire
        return $this->render('admin/product/addproduct.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/product/{id}/edit', name: 'product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, $id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);

        // Vérifiez si l'entité existe
        if (!$product) {
            throw $this->createNotFoundException('Aucun produit trouvé pour cet identifiant : ' . $id);
        }

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_product', ['id' => $product->getId()]);
        }

        return $this->render('admin/product/addproduct.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }
  
    
}
