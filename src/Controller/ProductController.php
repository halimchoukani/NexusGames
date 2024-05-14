<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProductRepository;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ProduitType;
use Doctrine\Persistence\ManagerRegistry;

class ProductController extends AbstractController
{
    private $doctrine;
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }


    #[Route('/products', name: 'app_produit')]
    public function index(ProductRepository $productRepository): Response
    {
        $produits = $productRepository->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $produits,
            'image_directory' => $this->getParameter('images_directory'),
        ]);
    }

    #[Route('/product/{id}', name: 'app_produit_show')]
    public function show($id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        return $this->render('product/product.html.twig', [
            'product' => $product,
            'image_directory' => $this->getParameter('images_directory'),
        ]);
    }

    #[Route('/product/add', name: 'app_produit_ajouter')]
    public function ajouter(Request $request): Response
    {
        $produit = new Product();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $produit = $form->getData();
            //****************Manage Uploaded FileName
            $photo_prod = $form->get('image')->getData();
            $originalFilename = $photo_prod->getClientOriginalName();
            $newFilename = $originalFilename . '-' . uniqid() . '.' . $photo_prod->getClientOriginalExtension();
            $photo_prod->move($this->getParameter('images_directory'), $newFilename);
            $produit->setImage($newFilename);
            //****************
            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($produit);
            $entityManager->flush();
        }
        return $this->render('product/ajouter.html.twig', ['form' => $form->createView(),]);
    }
}
