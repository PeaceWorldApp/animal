<?php

namespace App\Repository;

use App\Entity\CatNote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Driver\Mysqli\Connection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CatNote>
 *
 * @method CatNote|null find($id, $lockMode = null, $lockVersion = null)
 * @method CatNote|null findOneBy(array $criteria, array $orderBy = null)
 * @method CatNote[]    findAll()
 * @method CatNote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CatNoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CatNote::class);
    }

    public function add(CatNote $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CatNote $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
    * @return CatNote[] Returns an array of CatNote objects
    */
   public function findAllArray(): array
   {
       return $this->createQueryBuilder('c')
       ->select('c.id, c.name')
           ->getQuery()
           ->getResult()
       ;
   }

//    /**
//     * @return CatNote[] Returns an array of CatNote objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CatNote
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
