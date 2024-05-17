<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="app_cart")
     */
    public function index(Request $request,ProductRepository $productRepository): Response
    {
        $session = $request->getSession();
        $panier = $session->get('panier', []);
        $total = 0;
        $panierData = [];
        foreach ($panier as $id => $quantity) {
            $panierData[] = [
                'id' => $id,
                'product' => $productRepository->find($id),
                'quantity' => $quantity,
            ];
            $total += $productRepository->find($id)->getPrix() * $quantity;
        }
        return $this->render('cart/index.html.twig', [
            'items' =>
            $panierData
            ,
            'total' => $total,
        ]);
    }
    /**
     * @Route("/add", name="add_cart")
     */
    public function add($id, Request $request)
    {
        $session = $request->getSession();
        $panier = $session->get('panier', []);
        if (!empty($panier[$id]))
            $panier[$id]++;
        else
            $panier[$id] = 1;
        $session->set('panier', $panier);
        return $this->redirectToRoute('app_cart');
    }

    /**
     * @Route("/remove/{id}", name="remove_cart")
     */
    public function remove($id, Request $request)
    {
        $session = $request->getSession();
        $panier = $session->get('panier', []);
        if (!empty($panier[$id]))
            unset($panier[$id]);
        $session->set('panier', $panier);
        return $this->redirectToRoute('app_cart');
    }
    public function buy(Request $request,ProductRepository $productRepository)
    {
        $session = $request->getSession();
        $panier = $session->get('panier', []);
        foreach ($panier as $id => $quantity) {
            $productRepository->editQuantitie($id, $quantity);
        }
        $session->set('panier', []);
        return $this->redirectToRoute('app_buy');
    }


}
