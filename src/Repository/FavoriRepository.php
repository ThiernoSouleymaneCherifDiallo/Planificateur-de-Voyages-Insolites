<?php

namespace App\Repository;

use App\Entity\Favori;
use App\Entity\User;
use App\Entity\Destination;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Favori>
 *
 * @method Favori|null find($id, $lockMode = null, $lockVersion = null)
 * @method Favori|null findOneBy(array $criteria, array $orderBy = null)
 * @method Favori[]    findAll()
 * @method Favori[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavoriRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Favori::class);
    }

    public function save(Favori $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Favori $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Trouve tous les favoris d'un utilisateur
     */
    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.user = :user')
            ->setParameter('user', $user)
            ->orderBy('f.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Vérifie si une destination est dans les favoris d'un utilisateur
     */
    public function isFavori(User $user, Destination $destination): bool
    {
        $favori = $this->createQueryBuilder('f')
            ->andWhere('f.user = :user')
            ->andWhere('f.destination = :destination')
            ->setParameter('user', $user)
            ->setParameter('destination', $destination)
            ->getQuery()
            ->getOneOrNullResult();

        return $favori !== null;
    }

    /**
     * Trouve un favori spécifique pour un utilisateur et une destination
     */
    public function findByUserAndDestination(User $user, Destination $destination): ?Favori
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.user = :user')
            ->andWhere('f.destination = :destination')
            ->setParameter('user', $user)
            ->setParameter('destination', $destination)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Trouve les favoris publics (pour affichage communautaire)
     */
    public function findPublicFavoris(int $limit = 10): array
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.isPublic = :isPublic')
            ->setParameter('isPublic', true)
            ->orderBy('f.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les destinations les plus favorisées
     */
    public function findMostFavoritedDestinations(int $limit = 5): array
    {
        $conn = $this->getEntityManager()->getConnection();
        
        $sql = '
            SELECT d.id, d.name, d.city, d.country, d.type, d.budget_level, d.duration, d.climate, d.image_url, COUNT(f.id) as favoriCount
            FROM destination d
            LEFT JOIN favori f ON f.destination_id = d.id
            GROUP BY d.id
            ORDER BY favoriCount DESC
            LIMIT :limit
        ';
        
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('limit', $limit, \PDO::PARAM_INT);
        $result = $stmt->executeQuery();
        
        return $result->fetchAllAssociative();
    }

    /**
     * Trouve les favoris récents d'un utilisateur
     */
    public function findRecentByUser(User $user, int $limit = 5): array
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.user = :user')
            ->setParameter('user', $user)
            ->orderBy('f.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
} 