<?php
declare(strict_types=1);


namespace App\Repository;

use App\Entity\User;
use App\Exception\UserNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class UserRepository
 * @package App\Repository
 */
class UserRepository extends ServiceEntityRepository
{
    /**
     * UserRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param int $userId
     * @return User
     * @throws UserNotFoundException
     */
    public function getOneById(int $userId): User
    {
        $user = parent::find($userId);

        if (!$user) {
            throw new UserNotFoundException('Does user not found');
        }

        return $user;
    }

    /**
     * @param string $firstToken
     * @param string $secondToken
     * @return int
     */
    public function getCountByTokens(string $firstToken, string $secondToken): int
    {
        return $this->getEntityManager()
            ->createQuery('
                SELECT COUNT(p) FROM App:USER p WHERE p.token = :firstToken OR p.token = :secondToken
            ')
            ->setParameter(':firstToken', $firstToken)
            ->setParameter(':secondToken', $secondToken)
            ->execute();
    }

    /**
     * @param string $token
     * @return User
     */
    public function getOneByToken(string $token): User
    {
        $user = $this->findOneBy(['tokenMessenger' => $token]);

        if (is_null($user)) {
            throw new UserNotFoundException('User not found by token');
        }

        return $user;
    }
}
