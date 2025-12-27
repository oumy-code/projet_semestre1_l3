<?php

namespace App\Repository;

use App\Repository\Interface\GestionnaireRepositoryInterface;
use App\Entity\Gestionnaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * Repository pour les gestionnaires
 */
class GestionnaireRepository extends ServiceEntityRepository implements GestionnaireRepositoryInterface, PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gestionnaire::class);
    }

    public function findByLogin(string $login): ?Gestionnaire
    {
        return $this->createQueryBuilder('g')
            ->where('g.login = :login')
            ->setParameter('login', $login)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function save(Gestionnaire $gestionnaire): void
    {
        $this->getEntityManager()->persist($gestionnaire);
        $this->getEntityManager()->flush();
    }

    public function remove(Gestionnaire $gestionnaire): void
    {
        $this->getEntityManager()->remove($gestionnaire);
        $this->getEntityManager()->flush();
    }

    /**
     * Utilisé pour mettre à jour le mot de passe automatiquement.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Gestionnaire) {
            throw new \LogicException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setMotDePasse($newHashedPassword);
        $this->save($user);
    }
}