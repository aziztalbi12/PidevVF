<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function searchByType($query)
    {
        return $this->createQueryBuilder('p')
            ->where('p.nom LIKE :query OR p.description LIKE :query ')
            ->setParameter('query', $query.'%')
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findAllSortedByPriceASC()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.prix', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findAllSortedByPriceDESC()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.prix', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /*public function getStatsByType()
    {
        $qb = $this->createQueryBuilder('p')
            ->select('c.nom as category, count(p) as count')
            ->leftJoin('p.id', 'c')
            ->groupBy('c.category_id');

        return $qb->getQuery()->getResult();
    }*/

//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
