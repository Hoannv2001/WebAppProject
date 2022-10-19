<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\User;
use App\Form\BookType;
use App\Repository\BookRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/book")
 */
class BookController extends AbstractController
{
    /**
     * @Route("/list/", name="app_book_index", methods={"GET"})
     */
    public function index(BookRepository $bookRepository, LoggerInterface $logger): Response
    {
        return $this->render('book/index.html.twig', [
            'books' => $bookRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_book_new", methods={"GET", "POST"})
     */
    public function new(Request $request, BookRepository $bookRepository, LoggerInterface $logger): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        $user = $this->getUser();
        $bookRepository->add($book->setOwner($user));
        if (is_null($user)){
            return $this->redirectToRoute('app_login',[],Response::HTTP_SEE_OTHER);
        }
        elseif($form->isSubmitted() && $form->isValid() ) {
            $bookRepository->add($book, true);

            return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('book/new.html.twig', [
            'book' => $book,
            'form' => $form,

        ]);
    }

    /**
     * @Route("/{id}", name="app_book_show", methods={"GET"})
     */
    public function show(Book $book): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_book_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Book $book, BookRepository $bookRepository): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        $user = $this->getUser();
        if (is_null($user)){
            return $this->redirectToRoute('app_login',[],Response::HTTP_SEE_OTHER);
        }elseif ($user !== $book->getOwner()){
            return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
        }
        elseif ($form->isSubmitted() && $form->isValid()) {
            $bookRepository->add($book, true);
            return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('book/edit.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }
    /**
     * @Route("/{id}", name="app_book_delete", methods={"POST"})
     */
    public function delete(Request $request, Book $book, BookRepository $bookRepository): Response
    {
        $user = $this->getUser();
        if (is_null($user)){
            return $this->redirectToRoute('app_login',[],Response::HTTP_SEE_OTHER);
        }elseif ($user !== $book->getOwner()){
            return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->request->get('_token'))) {
            $bookRepository->remove($book, true);
        }

        return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
    }
}
