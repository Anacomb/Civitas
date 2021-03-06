<?php

namespace App\Repository;

use App\Entity\Personne;
use App\Entity\Commune;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Personne|null find($id, $lockMode = null, $lockVersion = null)
 * @method Personne|null findOneBy(array $criteria, array $orderBy = null)
 * @method Personne[]    findAll()
 * @method Personne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Personne::class);
    }

    // /**
    //  * @return Personne[] Returns an array of Personne objects
    //  */
    
    /*public function findProblemesByCommune(Commune $commune)
    {
        return $this->createQueryBuilder('p')
            ->join('p.Commune = :commune')
            ->setParameter('commune', $commune)
            ->getQuery()
            ->getResult()
        ;
    }*/

    /*
    public function findOneBySomeField($value): ?Personne
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function getPersonneByPermission($permission){
        return $this->createQueryBuilder('p')
            ->Join('p.Roles','r')
            ->Join('r.Permissions','perms')
            ->where('perms.permission = :permission')
            ->setParameter('permission',$permission)
            ->getQuery()
            ->getResult();

    }

}
