<?php

namespace App\Repository;

use App\Entity\HistoriqueStatut;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HistoriqueStatut|null find($id, $lockMode = null, $lockVersion = null)
 * @method HistoriqueStatut|null findOneBy(array $criteria, array $orderBy = null)
 * @method HistoriqueStatut[]    findAll()
 * @method HistoriqueStatut[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoriqueStatutRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HistoriqueStatut::class);
    }

    // /**
    //  * @return HistoriqueStatut[] Returns an array of HistoriqueStatut objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HistoriqueStatut
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findLatestHistoriqueStatutByProblem(){
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
       SELECT *
        FROM historique_statut AS t1
        LEFT OUTER JOIN 
            (
                SELECT probleme_id, MAX(date) as maxdate
                FROM historique_statut
                GROUP BY probleme_id
            ) 
            AS t2 USING (probleme_id)
        WHERE t1.date = t2.maxdate
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();

    }
}
