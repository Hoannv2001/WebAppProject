<?php

namespace App\Controller;

use App\Entity\Book;
use phpDocumentor\Reflection\Types\This;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="app_cart", methods={"GET"})
     */
    public function index(Request $request,LoggerInterface $logger): Response
    {
        $book = new Book();
        $session = $request->getSession();
        $user = $this->getUser();
        $dayOrder =  new \DateTime();
        $quantity = $request->query->get('quantity');
        $idBook = (int)$request->query->get('idBook');
        $priceBook = (float)$request->query->get('priceOfBook');
        $totalPrice = $quantity * $priceBook;
        if (is_null($totalPrice))
            $logger->info("User nooooo");
        else
            $logger->info("User's email quality ".$totalPrice);
        $logger->info($idBook);
        $logger->info($priceBook);
        $logger->info($quantity);
        $logger->info($user->getUserIdentifier());
//        $array = [$idBook,$totalPrice];
        if (!$session->has('cartElements')) {
            $cartElements = $session->set('cartElements', $idBook);
            $session->set('cartElements', $cartElements);
        }
        else {
            $cartElements = $session->get('cartElements');
            //Add new product after the first time. (would UPDATE new quantity for added product)
            $cartElements = array($book->getId() => $idBook) + $cartElements;
            //Re-save cart Elements back to session again (after update/append new product to shopping cart)
            $session->set('cartElements', $cartElements);

        }
//        return $this->json($cartElements);
        return new Response();
        }
    /**
     * @Route("/reviewCart", name="app_review_cart", methods={"GET"})
     */
    public function reviewCart(Request $request): Response
    {
        $session = $request->getSession();
        if ($session->has('cartElements')) {
            $cartElements = $session->get('cartElements');
        } else
            $cartElements = [];
        return $this->json($cartElements);
    }

}
