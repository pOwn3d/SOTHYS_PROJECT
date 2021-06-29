<?php

namespace App\Repository;

use App\Entity\CustomerIncoterm;
use App\Entity\Incoterm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @method CustomerIncoterm|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomerIncoterm|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomerIncoterm[]    findAll()
 * @method CustomerIncoterm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerIncotermRepository extends ServiceEntityRepository
{
    /**
     * @var \Symfony\Component\Security\Core\Security
     */
    private Security $security;

    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, CustomerIncoterm::class);
        $this->security = $security;
    }
//   En cours de refactorisation
//    public function getIncotermSociety(){
//
//       return $this->createQueryBuilder('c')
//            ->innerJoin(Incoterm::class, 'i', Join::WITH, 'i.id = c.reference')
//            ->andWhere('c.societyCustomerIncoterm = ' . $this->security->getUser()->getSocietyId()->getId())
//            ->getQuery()
//            ->getResult()
//            ;
//    }

    // /**
    //  * @return CustomerIncoterm[] Returns an array of CustomerIncoterm objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CustomerIncoterm
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
