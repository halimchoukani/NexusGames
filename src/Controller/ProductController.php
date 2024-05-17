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
            'title' => 'All Products',
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
            $produit->setDateInsertion(new \DateTime());
            //****************Manage Uploaded FileName
            $photo_prod = $form->get('image')->getData();
            $originalFilename = $photo_prod->getClientOriginalName();
            $newFilename = uniqid() . '.' . $photo_prod->getClientOriginalExtension();
            $photo_prod->move($this->getParameter('images_directory'), $newFilename);
            $produit->setImage($newFilename);
            //****************
            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($produit);
            $entityManager->flush();
        }
        return $this->render('product/ajouter.html.twig', ['form' => $form->createView(),]);
    }

    #[Route('/products/mouses', name: 'app_product_mouse')]
    public function showMouses(ProductRepository $productRepository): Response
    {
        $produits = $productRepository->findBy(['categorie' => 'mouse']);
        return $this->render('product/index.html.twig', [
            'products' => $produits,
            'image_directory' => $this->getParameter('images_directory'),
            'title' => 'Mouses',
        ]);
    }

    #[Route('/products/keyboards', name: 'app_product_keyboard')]
    public function showKeyboards(ProductRepository $productRepository): Response
    {
        $produits = $productRepository->findBy(['categorie' => 'keyboard']);
        return $this->render('product/index.html.twig', [
            'products' => $produits,
            'image_directory' => $this->getParameter('images_directory'),
            'title' => 'Keyboards',
        ]);
    }

    #[Route('/products/headsets', name: 'app_product_headset')]
    public function showHeadsets(ProductRepository $productRepository): Response
    {
        $produits = $productRepository->findBy(['categorie' => 'headset']);
        return $this->render('product/index.html.twig', [
            'products' => $produits,
            'image_directory' => $this->getParameter('images_directory'),
            'title' => 'Headsets',
        ]);
    }
    #[Route('/products/mousepads', name: 'app_product_mousepad')]
    public function showMousepads(ProductRepository $productRepository): Response
    {
        $produits = $productRepository->findBy(['categorie' => 'mousepad']);
        return $this->render('product/index.html.twig', [
            'products' => $produits,
            'image_directory' => $this->getParameter('images_directory'),
            'title' => 'Mousepads',
        ]);
    }
    #[Route('/products/screens', name: 'app_product_screen')]
    public function showScreens(ProductRepository $productRepository): Response
    {
        $produits = $productRepository->findBy(['categorie' => 'screen']);
        return $this->render('product/index.html.twig', [
            'products' => $produits,
            'image_directory' => $this->getParameter('images_directory'),
            'title' => 'Screens',
        ]);
    }

}
