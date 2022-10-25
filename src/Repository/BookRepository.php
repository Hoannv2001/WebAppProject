<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function add(Book $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Book $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function countOfBook(): Query
    {
        $entityManager = $this->getEntityManager();
        $qb = $entityManager->createQueryBuilder();
        $qb->select('b')
            ->from('App:Book', 'b');
        return $qb->getQuery();

    }
    public function selectDataBookAdmin($user):Query
    {
        $entityManager = $this->getEntityManager();
        $qb = $entityManager->createQueryBuilder();
        $qb->select('b')
            ->from('App:Book', 'b')
            ->where('b.user = :user')
            ->setParameter('user', $user);
        return $qb->getQuery();
    }
    public  function findAllPriceRange($minPrice,$maxPrice,$cat): Query
    {
        $entityManager = $this->getEntityManager();
        $qb = $entityManager->createQueryBuilder();
        $qb->select('b')
            ->from('App:Book', 'b');
        if (is_null($minPrice) || empty($minPrice)){
            $minPrice=0;
        }
        $qb->where('b.price >=' . $minPrice);
        if (!(is_null($maxPrice) || empty($maxPrice))){
            $qb->andWhere('b.price <=' . $maxPrice);
        }
        if (!(is_null($cat)||empty($cat))){
            $qb->andWhere('b.category =' . $cat);
        }
        return $qb->getQuery();
    }
    public function findPriceOfId($idBook):Query
    {
        $entityManager = $this->getEntityManager();
        $qb = $entityManager->createQueryBuilder();
        $qb->select('b')
            ->from('App:Book', 'b')
            ->where('b.price =' . $idBook);
        return $qb->getQuery();
    }

//    /**
//     * @return Book[] Returns an array of Book objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
