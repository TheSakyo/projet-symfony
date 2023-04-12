<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface {

    public function __construct(ManagerRegistry $registry) { parent::__construct($registry, User::class); }

                /* ----------------------------------------------------------------------- */

    public function save(User $entity, bool $flush = false): void {

        $this->getEntityManager()->persist($entity);
        if($flush) { $this->getEntityManager()->flush(); }
    }

    public function remove(User $entity, bool $flush = false): void {

        $this->getEntityManager()->remove($entity);
        if($flush) { $this->getEntityManager()->flush(); }
    }
    
                /* ----------------------------------------------------------------------- */

    /**
     * Utilisé pour mettre à jour automatiquement le mot de passe de l'utilisateur au fil du temps.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void {

        if(!$user instanceof User) { throw new UnsupportedUserException(sprintf('Les instances de "%s" ne sont pas prises en charge.', \get_class($user))); }

        $user->setPassword($newHashedPassword);
        $this->save($user, true);
    }
}
