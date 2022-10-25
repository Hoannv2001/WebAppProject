<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Order;
use App\Form\OrderType;
use App\Repository\BookRepository;
use App\Repository\OrderRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/order")
 */
class OrderController extends AbstractController
{
    /**
     * @Route("/", name="app_order_index", methods={"GET"})
     */
    public function index(OrderRepository $orderRepository): Response
    {
        return $this->render('order/index.html.twig', [
            'orders' => $orderRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_order_new", methods={"GET", "POST"})
     */
    public function new(Request $request,BookRepository $bookRepository, OrderRepository $orderRepository, LoggerInterface $logger): Response
    {
        $book = new Book();
        $order = new Order();
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        $user = $this->getUser();
        $dayOrder =  new \DateTime();
        $quantity = $request->query->get('quantity');
        $idBook = (int)$request->query->get('idBook');
        $priceBook = (float)$request->query->get('priceOfBook');
        $totalPrice = $quantity * $priceBook;

        $orderRepository->add($order->setCustomer($user));
        $orderRepository->add($order->setDateOrder($dayOrder));
        $orderRepository->add($order->setQuality($quantity));
        $orderRepository->add($order->setTotalPrice($totalPrice));
//        $orderRepository->add($order->addOrderBook($book));
//        $order->addOrderBook($);

//        $priceBook = $bookRepository->findPriceOfId($idBook);
//        (float)$priceBook->getResult();

        if (is_null($totalPrice))
            $logger->info("User nooooo");
        else
            $logger->info("User's email quality ".$totalPrice);
        $logger->info($idBook);
        $logger->info($priceBook);
        $logger->info($quantity);
        $logger->info($user->getUserIdentifier());

        if ($form->isSubmitted() && $form->isValid()) {
            $orderRepository->add($order, true);

            return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('order/new.html.twig', [
            'order' => $order,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_order_show", methods={"GET"})
     */
    public function show(Order $order): Response
    {
        return $this->render('order/show.html.twig', [
            'order' => $order,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_order_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Order $order, OrderRepository $orderRepository): Response
    {
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $orderRepository->add($order, true);

            return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('order/edit.html.twig', [
            'order' => $order,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_order_delete", methods={"POST"})
     */
    public function delete(Request $request, Order $order, OrderRepository $orderRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$order->getId(), $request->request->get('_token'))) {
            $orderRepository->remove($order, true);
        }

        return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
    }
}
