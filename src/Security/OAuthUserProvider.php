<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

class OAuthUserProvider extends ServiceEntityRepository implements UserLoaderInterface, OAuthAwareUserProviderInterface
{
    private $logger;

    public function __construct(ManagerRegistry $registry, LoggerInterface $logger)
    {
        parent::__construct($registry, User::class);
        $this->logger = $logger;
    }

    public function loadUserByIdentifier(string $identifier): User
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $identifier)
            ->setParameter('email', $identifier)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function loadUserByOAuthUserResponse(UserResponseInterface $response): User
    {
        $identifier = $response->getEmail();
        $user = $this->createQueryBuilder('u')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $identifier)
            ->setParameter('email', $identifier)
            ->getQuery()
            ->getOneOrNullResult();

        if(!$user) {
            $user = new User();
            $user->setEmail($response->getEmail());
            $user->setFirstname($response->getFirstName());
            $user->setLastname($response->getLastName());
            $user->setUsername($response->getUserName());
            $user->setNickname(!$response->getNickname()? $response->getNickname(): strtok($response->getEmail(), '@'));
            $user->setIsVerified(false);
        }
        
        if(!$user->isVerified()) {
            $user->setIsVerified(true);
            $user->setIsEmailVerificationSent(true);
            $this->_em->persist($user);
            $this->_em->flush();
        }

        return $user;
    }
}
