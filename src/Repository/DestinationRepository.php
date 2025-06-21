<?php

namespace App\Repository;

use App\Entity\Destination;
use App\Entity\Preference;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Destination>
 *
 * @method Destination|null find($id, $lockMode = null, $lockVersion = null)
 * @method Destination|null findOneBy(array $criteria, array $orderBy = null)
 * @method Destination[]    findAll()
 * @method Destination[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DestinationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Destination::class);
    }

    public function save(Destination $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Destination $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Trouver des destinations par type
     */
    public function findByType(string $type): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.type = :type')
            ->setParameter('type', $type)
            ->orderBy('d.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouver des destinations par budget
     */
    public function findByBudgetLevel(int $budgetLevel): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.budgetLevel = :budgetLevel')
            ->setParameter('budgetLevel', $budgetLevel)
            ->orderBy('d.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouver des destinations par climat
     */
    public function findByClimate(string $climate): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.climate = :climate')
            ->setParameter('climate', $climate)
            ->orderBy('d.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche avancée avec plusieurs critères
     */
    public function searchDestinations(array $criteria): array
    {
        $qb = $this->createQueryBuilder('d');

        if (!empty($criteria['type'])) {
            $qb->andWhere('d.type = :type')
               ->setParameter('type', $criteria['type']);
        }

        if (!empty($criteria['budgetLevel'])) {
            $qb->andWhere('d.budgetLevel = :budgetLevel')
               ->setParameter('budgetLevel', $criteria['budgetLevel']);
        }

        if (!empty($criteria['climate'])) {
            $qb->andWhere('d.climate = :climate')
               ->setParameter('climate', $criteria['climate']);
        }

        if (!empty($criteria['country'])) {
            $qb->andWhere('d.country LIKE :country')
               ->setParameter('country', '%' . $criteria['country'] . '%');
        }

        return $qb->orderBy('d.name', 'ASC')
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Rechercher des destinations selon les préférences utilisateur
     */
    public function findByPreferences(Preference $preference): array
    {
        $qb = $this->createQueryBuilder('d');

        // Filtre par climat
        if ($preference->getClimate()) {
            $qb->andWhere('d.climate = :climate')
               ->setParameter('climate', $preference->getClimate());
        }

        // Filtre par budget (convertir budgetLevel en plage de prix approximative)
        if ($preference->getMinBudget() && $preference->getMaxBudget()) {
            // Conversion approximative : 1=économique, 2=moyen, 3=luxe
            $minBudgetLevel = $this->getBudgetLevelFromAmount($preference->getMinBudget());
            $maxBudgetLevel = $this->getBudgetLevelFromAmount($preference->getMaxBudget());
            
            $qb->andWhere('d.budgetLevel BETWEEN :minBudget AND :maxBudget')
               ->setParameter('minBudget', $minBudgetLevel)
               ->setParameter('maxBudget', $maxBudgetLevel);
        }

        // Filtre par durée
        if ($preference->getMinDuration() && $preference->getMaxDuration()) {
            $qb->andWhere('d.duration BETWEEN :minDuration AND :maxDuration')
               ->setParameter('minDuration', $preference->getMinDuration())
               ->setParameter('maxDuration', $preference->getMaxDuration());
        }

        // Filtre par pays préféré
        if ($preference->getPreferredCountry()) {
            $qb->andWhere('d.country LIKE :country')
               ->setParameter('country', '%' . $preference->getPreferredCountry() . '%');
        }

        // Filtre par types de voyage
        $preferredTypes = $preference->getPreferredTypesArray();
        if (!empty($preferredTypes)) {
            $qb->andWhere('d.type IN (:types)')
               ->setParameter('types', $preferredTypes);
        }

        return $qb->orderBy('d.name', 'ASC')
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Convertir un montant en niveau de budget approximatif
     */
    private function getBudgetLevelFromAmount(int $amount): int
    {
        if ($amount <= 1000) {
            return 1; // Économique
        } elseif ($amount <= 3000) {
            return 2; // Moyen
        } else {
            return 3; // Luxe
        }
    }
} 