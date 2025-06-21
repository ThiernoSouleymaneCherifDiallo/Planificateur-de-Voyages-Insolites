<?php

namespace App\Repository;

use App\Entity\Planification;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Planification>
 *
 * @method Planification|null find($id, $lockMode = null, $lockVersion = null)
 * @method Planification|null findOneBy(array $criteria, array $orderBy = null)
 * @method Planification[]    findAll()
 * @method Planification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Planification::class);
    }

    public function save(Planification $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Planification $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Trouve toutes les planifications d'un utilisateur
     */
    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.user = :user')
            ->setParameter('user', $user)
            ->orderBy('p.dateDepart', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les planifications à venir d'un utilisateur
     */
    public function findUpcomingByUser(User $user): array
    {
        $now = new \DateTime();
        
        return $this->createQueryBuilder('p')
            ->andWhere('p.user = :user')
            ->andWhere('p.dateDepart > :now')
            ->setParameter('user', $user)
            ->setParameter('now', $now)
            ->orderBy('p.dateDepart', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les planifications en cours d'un utilisateur
     */
    public function findCurrentByUser(User $user): array
    {
        $now = new \DateTime();
        
        return $this->createQueryBuilder('p')
            ->andWhere('p.user = :user')
            ->andWhere('p.dateDepart <= :now')
            ->andWhere('p.dateRetour >= :now')
            ->setParameter('user', $user)
            ->setParameter('now', $now)
            ->orderBy('p.dateDepart', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les planifications terminées d'un utilisateur
     */
    public function findCompletedByUser(User $user): array
    {
        $now = new \DateTime();
        
        return $this->createQueryBuilder('p')
            ->andWhere('p.user = :user')
            ->andWhere('p.dateRetour < :now')
            ->setParameter('user', $user)
            ->setParameter('now', $now)
            ->orderBy('p.dateRetour', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les planifications par statut pour un utilisateur
     */
    public function findByUserAndStatus(User $user, string $status): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.user = :user')
            ->andWhere('p.statut = :status')
            ->setParameter('user', $user)
            ->setParameter('status', $status)
            ->orderBy('p.dateDepart', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les planifications pour une destination spécifique
     */
    public function findByDestination(int $destinationId): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.destination = :destinationId')
            ->setParameter('destinationId', $destinationId)
            ->orderBy('p.dateDepart', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les planifications dans une période donnée
     */
    public function findByDateRange(\DateTime $startDate, \DateTime $endDate): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.dateDepart >= :startDate')
            ->andWhere('p.dateRetour <= :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->orderBy('p.dateDepart', 'ASC')
            ->getQuery()
            ->getResult();
    }
} 