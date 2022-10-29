<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Order;
use App\Entity\OrderItems;
use App\Repository\BookRepository;
use App\Repository\OrderItemsRepository;
use App\Repository\OrderRepository;
use Exception;
use phpDocumentor\Reflection\Types\This;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
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
        if (!$session->has('cartElements')) {
            //if it is empty, create an array of pairs (prod Id & quantity) to store first cart element.
            $cartElements = array($book->getId() => $quantity);
            $session->set('cartElements', $cartElements);
            //save the array to the session for the first time.
        } else {
            $cartElements = $session->get('cartElements');
            //Add new product after the first time. (would UPDATE new quantity for added product)
            $cartElements = array($book->getId() => $quantity) + $cartElements;
            //Re-save cart Elements back to session again (after update/append new product to shopping cart)
        }
        $session->set('cartElements', $cartElements);
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
        $idBook= (int)$request->query->get('idBook');
        $quantity = (int)$request->query->get('quantity');

        $logger->info($quantity);

        $logger->info("id:".$idBook);
        $temQuery = $bookRepository->findInfoBook($idBook);
        $session = $request->getSession();
        if ($session->has('cartElements')) {
            $cartElements = $session->get('cartElements');
        } else
            $cartElements = [];

        return $this->render('cart/index.html.twig', [
            'bookInfos'=>$temQuery->getResult(),
            'quantity'=>$cartElements,
        ]);
//        return $this->json($cartElements);
    }
    /**
     * @Route("/removeCart", name="app_remove_cart", methods={"GET"})
     */
    public function removeCar(Request $request, BookRepository $bookRepository, LoggerInterface $logger)
    {
        $session = $request->getSession();
        $idBook= $request->query->get('id');
        $logger->info($idBook);
        if ($session->has('cartElements')) {
            $cartElements = $session->get('cartElements');

            unset($cartElements[$idBook]);
            $cartElements = $session->set('cartElements', $cartElements);
        }
        return $this->redirectToRoute('app_review_cart',[],Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/checkoutCart", name="app_checkout_cart", methods={"GET"})
     */
    public function checkoutCart(Request               $request,
                                 OrderItemsRepository $orderItemsRepository,
                                 OrderRepository       $orderRepository,
                                 BookRepository     $bookRepository,
                                    LoggerInterface $logger)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $session = $request->getSession(); //get a session
        if ($session->has('cartElements') && !empty($session->get('cartElements'))) {
            try {
                $cartElements = $session->get('cartElements');

                $order = new Order();
                date_default_timezone_set('Asia/Ho_Chi_Minh');
                $order->setDateOrder(new \DateTime());
                $user = $this->getUser();
                $order->setCustomer($user);
                $total = 0;
                foreach ($cartElements as $book_id => $quantity) {
                    $book = $bookRepository->find($book_id);
                    //create each Order Detail
                    $orderItem = new OrderItems();
                    $orderItem->setOrderB($order);
                    $orderItem->setBook($book);
                    $orderItem->setQuantity($quantity);
                    $orderItemsRepository->add($orderItem);

                    $total += $book->getPrice() * $quantity;
                }
                $order->setTotalPaymet($total);
                $orderRepository->add($order,true);
                $session->remove('cartElements');
//                $logger->info($user);
            } catch (Exception $e) {

            }
            return $this->redirectToRoute('app_review_cart',[],Response::HTTP_SEE_OTHER);
        } else
            return $this->redirectToRoute('app_book_index',[],Response::HTTP_SEE_OTHER);
    }

}
