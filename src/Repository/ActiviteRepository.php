<?php

namespace App\Repository;

use App\Entity\Activite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Activite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Activite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Activite[]    findAll()
 * @method Activite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActiviteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Activite::class);
    }
    
    public function findOneBySlugAndIdAndUser($slug, $id, $user){
        
        $qb = $this->createQueryBuilder('a')
                ->select('a')
                ->leftJoin('a.voyage', 'v')
                ->where('a.slug = :slug')
                ->andWhere('a.id = :id')
                ->andWhere('v.user = :user')
                ->setParameters(array(
                    'slug' => $slug,
                    'user' => $user,
                    'id' => $id,
                ));
        
        return $qb->getQuery()->getOneOrNullResult();
    }

    // /**
    //  * @return Activite[] Returns an array of Activite objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Activite
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
