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
        $temQuery = $bookRepository->countOfBook();
        $paginator = new Paginator($temQuery);
        $totalItems = count($paginator);

        $temQuery1 = $authorRepository->countOfAuthor();
        $paginator = new Paginator($temQuery1);
        $totalItems1 = count($paginator);

        $temQuery2 = $categoryRepository->countOfCategory();
        $paginator = new Paginator($temQuery2);
        $totalItems2 = count($paginator);

//        /**
//         * @var\App\Entity\User
//         */
//        $user = $this->getUser();
//        if (is_null($user)){
//            $logger->info("nooooo");
//        }else{
//            $logger->info("yes".$user->getUserIdentifier());
//        }
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'countOfBook' =>$totalItems,
            'countOfAuthor'=>$totalItems1,
            'countOfCategory'=>$totalItems2,

        ]);
    }
}
