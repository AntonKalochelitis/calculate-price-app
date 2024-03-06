<?php

namespace App\Repository;

use App\Entity\TypeCoupon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypeCoupon>
 *
 * @method TypeCoupon|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeCoupon|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeCoupon[]    findAll()
 * @method TypeCoupon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeCouponRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeCoupon::class);
    }

//    /**
//     * @return TypeCoupon[] Returns an array of TypeCoupon objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TypeCoupon
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
