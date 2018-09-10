<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class PostRepository
 */
class PostRepository extends ServiceEntityRepository
{
    /**
     * PostRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @return array|null
     */
    public function findAll(): ?array
    {
        return $this->getEntityManager()
            ->createQuery('SELECT p FROM App:Post p ORDER BY p.createdAt DESC')
            ->setMaxResults(22)
            ->execute()
            ;
    }
}
