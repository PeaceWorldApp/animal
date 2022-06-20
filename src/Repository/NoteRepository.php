<?php

namespace App\Repository;

use App\Entity\Note;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Note>
 *
 * @method Note|null find($id, $lockMode = null, $lockVersion = null)
 * @method Note|null findOneBy(array $criteria, array $orderBy = null)
 * @method Note[]    findAll()
 * @method Note[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Note::class);
    }

    public function add(Note $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Note $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    /**
     * @return Note[]
     */
    public function findAllGreaterThan(int $time): array
    {
        $entityManager = $this->getEntityManager();

        //1
        // $query = $entityManager->createQuery(
        //     'SELECT p
        //     FROM App\Entity\Note p
        //     WHERE p.repeatTime > :time
        //     ORDER BY p.content ASC'
        // )->setParameter('time', $time);

        // // returns an array of Product objects
        // return $query->getResult();

        //2
        $qb = $this->createQueryBuilder('p')
        ->select('p.content,p.author')
            ->where('p.repeatTime > :time')
            ->setParameter('time', $time)
            ->orderBy('p.content', 'ASC')
            // ->andWhere('p.author = :author')
            // ->setParameter('author', "Khanh")
            ;
        $results = $qb->getQuery()->getArrayResult();
        // return $qb->getQuery()->execute();
        return $results;
    }

//    /**
//     * @return Note[] Returns an array of Note objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Note
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
