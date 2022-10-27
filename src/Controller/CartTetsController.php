<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartTetsController extends AbstractController
{
    /**
     * @Route("/cart/tets", name="app_cart_tets")
     */
    public function index(): Response
    {
        return $this->render('cart_tets/index.html.twig', [
            'controller_name' => 'CartTetsController',
        ]);
    }
}
