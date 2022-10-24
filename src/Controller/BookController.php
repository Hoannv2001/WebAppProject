<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\User;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
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
     * @Route("/list/{page}", name="app_book_index", methods={"GET"})
     */
    public function index( Request $request, BookRepository $bookRepository,
                           int $page = 1 ): Response
    {
        $user=$this->getUser();

        $minPrice = $request->query->get('minPrice');
        $maxPrice = $request->query->get('maxPrice');
        $cat = $request->query->get('category');
        if(!(is_null($cat)||empty($cat))){
            $selectedCat=$cat;
        }else
            $selectedCat='';
        $temQuery = $bookRepository->findAllPriceRange($minPrice, $maxPrice, $cat);
        $pageSize = 4;
        $paginator = new Paginator($temQuery);
        $totalItems = count($paginator);
        $numOfPages = ceil($totalItems/$pageSize);
        $tempQuery = $paginator
            ->getQuery()
            ->setFirstResult($pageSize * ($page-1)) // set the offset
            ->setMaxResults($pageSize);

        $temQuery1 = $bookRepository->selectDataBookAdmin($user);

        $hasAccess = $this->isGranted('ROLE_SELLER');
        $hasAccessCus = $this->isGranted('ROLE_CUSTOMER');
        $hasAccessAdmin = $this->isGranted('ROLE_ADMIN');

        if ($hasAccessAdmin){
            return $this->render('book/index.html.twig', [
                'books'=>$bookRepository->findAll(),
            ]);

        }
        if ($hasAccess) {
            return $this->render('book/index.html.twig', [
                'books' => $temQuery1->getResult(),
            ]);
        }
        if ($hasAccessCus){
            return $this->render('book/bookCus.html.twig',[
                'books' => $tempQuery->getResult(),
                'selectedCat' => $selectedCat,
                'numOfPages' => $numOfPages,
                'totalItem' => $totalItems
            ]);

        }
        else {
            return $this->render('book/index.html.twig', [
                'books' => [],

            ]);
        }
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
        $bookRepository->add($book->setUser($user));
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
     * @Route("/{id}/show", name="app_book_show", methods={"GET"})
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
        $hasAccessAdmin = $this->isGranted('ROLE_ADMIN');
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        $user = $this->getUser();
        if (is_null($user)){
            return $this->redirectToRoute('app_login',[],Response::HTTP_SEE_OTHER);
        }
//        if ($user !== $book->getUser()){
//            return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
//        }
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
        }
        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->request->get('_token'))) {
            $bookRepository->remove($book, true);
        }
        return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
    }
}
