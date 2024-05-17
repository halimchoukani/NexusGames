<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProductRepository;
class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findNewProducts();
        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            'products' => $products,
        ]);
    }
}
