<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use phpDocumentor\Reflection\Types\This;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/addCart/{id}", name="app_add_cart", methods={"GET"})
     */
    public function addCart(Book $book,BookRepository $bookRepository, Request $request, LoggerInterface $logger)
    {
        $session = $request->getSession();


        $quantity = (int)$request->query->get('quantity');
        $cat = $request->query->get('category');
        $idBook = (int)$request->query->get('idBook');
        $page = (int)$request->query->get('pageWeb');
        $temQuery = $bookRepository->findAll();
        $pageSize = 4;
//        $paginator = new Paginator();
        $totalItems = count($temQuery);
        $logger->info("id: ".$idBook);
        $numOfPages = ceil($totalItems/$pageSize);
        $logger->info($numOfPages);
        $logger->info("a".$page);

        //check if cart is empty
        if (!$session->has('cartElements')) {
            //if it is empty, create an array of pairs (prod Id & quantity) to store first cart element.
            $cartElements = array($book->getId() => $quantity);
            //save the array to the session for the first time.
            $session->set('cartElements', $cartElements);
        } else {
            $cartElements = $session->get('cartElements');
            //Add new product after the first time. (would UPDATE new quantity for added product)
            $cartElements = array($book->getId() => $quantity) + $cartElements;
            //Re-save cart Elements back to session again (after update/append new product to shopping cart)
            $session->set('cartElements', $cartElements);
        }
        $c = count($cartElements);
//        $logger->info($cartElements());
        $session->set('count', $c);
        return $this->redirectToRoute('app_book_index', [
            'count'=>$c
        ]);
    }
    /**
     * @Route("/reviewCart", name="app_review_cart", methods={"GET"})
     */
    public function reviewCart(Request $request, BookRepository $bookRepository, LoggerInterface $logger): Response
    {
        $book = new  Book();
        $session = $request->getSession();
        $idBook= (int)$request->query->get('idOfBook');
        $quantity = (int)$request->query->get('quantityAfter');

        $logger->info($quantity);

        $logger->info("id:".$idBook);
        $temQuery = $bookRepository->findInfoBook($idBook);
        if ($session->has('cartElements')) {
            $cartElements = $session->get('cartElements');
//            $cartElements = array($book->getId() => $quantity) + $cartElements;
//            $session->set('cartElements', $cartElements);
        } else
            $cartElements = [];
//        return new Response($cartElements);
        return $this->render('cart/index.html.twig', [
            'bookInfos'=>$temQuery->getResult(),
            'quantity'=>$cartElements,
        ]);
    }

}
