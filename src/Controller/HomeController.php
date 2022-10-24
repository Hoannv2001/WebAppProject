<?php

namespace App\Controller;

use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use http\Env\Request;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="app_home")
     */
    public function index(BookRepository $bookRepository, AuthorRepository $authorRepository,
                            CategoryRepository $categoryRepository, LoggerInterface $logger): Response
    {
        $user=$this->getUser();
        $hasAccess = $this->isGranted('ROLE_SELLER');
        $hasAccessCus = $this->isGranted('ROLE_CUSTOMER');
        $hasAccessAdmin = $this->isGranted('ROLE_ADMIN');
        $temQuery = $bookRepository->selectDataBookAdmin($user);
        $paginator = new Paginator($temQuery);
        $totalItems = count($paginator);

        $temQueryBook = $bookRepository->countOfBook();
        $paginator = new Paginator($temQueryBook);
        $totalItemsBook = count($paginator);
        $temQuery1 = $authorRepository->countOfAuthor();
        $paginator = new Paginator($temQuery1);
        $totalItems1 = count($paginator);

        $temQuery2 = $categoryRepository->countOfCategory();
        $paginator = new Paginator($temQuery2);
        $totalItems2 = count($paginator);


        if ($hasAccess){
            return $this->render('home/index.html.twig',[
                'controller_name' => 'HomeController',
                'countOfBook' =>$totalItems,
                'countOfAuthor'=>$totalItems1,
                'countOfCategory'=>$totalItems2,
            ]);
        }elseif ($hasAccessCus){
            return $this->render('home/homeCus.html.twig',[
//                'controller_name' => 'HomeController',
                ]);
        }elseif ($hasAccessAdmin){
            return $this->render('home/index.html.twig',[
                'controller_name' => 'HomeController',
                'countOfBook' => $totalItemsBook,
                'countOfAuthor' => $totalItems1,
                'countOfCategory' => $totalItems2,
            ]);
        }
        else {
            return $this->render('home/homeCus.html.twig', []);
        }
    }
}
