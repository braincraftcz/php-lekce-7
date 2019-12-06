<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
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

    public function findByCentury($greaterOrEqualThan, $lessThanOrEqualThen)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.year >= :from')
            ->setParameter('from', $greaterOrEqualThan)
            ->andWhere('b.year <= :to')
            ->setParameter('to', $lessThanOrEqualThen)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getAuthors()
    {
        $result = $this->createQueryBuilder('b')
            ->select('b.author')
            ->groupBy('b.author')
            ->getQuery()
            ->getScalarResult()
        ;

        return array_column($result, "author");
    }

    public function findLast2Years()
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.year > :from')
            ->setParameter('from', date('Y') - 2)
            ->getQuery()
            ->getResult()
        ;
    }
}
