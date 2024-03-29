<?php

declare(strict_types=1);

namespace App\OAuth\Entity;

use DateTimeImmutable;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;

final class AuthCodeRepository implements AuthCodeRepositoryInterface
{
    /**
     * @var EntityRepository<AuthCode>
     */
    private EntityRepository $repo;
    private EntityManagerInterface $em;

    /**
     * @param EntityRepository<AuthCode> $repo
     */
    public function __construct(EntityManagerInterface $em, EntityRepository $repo)
    {
        $this->repo = $repo;
        $this->em = $em;
    }

    public function getNewAuthCode(): AuthCode
    {
        return new AuthCode();
    }

    /**
     * @throws UniqueTokenIdentifierConstraintViolationException
     */
    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity): void
    {
        if ($this->exists($authCodeEntity->getIdentifier())) {
            throw UniqueTokenIdentifierConstraintViolationException::create();
        }

        $this->em->persist($authCodeEntity);
        $this->em->flush();
    }

    public function revokeAuthCode($codeId): void
    {
        if ($code = $this->repo->find($codeId)) {
            $this->em->remove($code);
            $this->em->flush();
        }
    }

    public function isAuthCodeRevoked($codeId): bool
    {
        return !$this->exists($codeId);
    }

    public function removeAllForUser(string $userId): void
    {
        $this->em->createQueryBuilder()
            ->delete(AuthCode::class, 'ac')
            ->andWhere('ac.userIdentifier = :user_id')
            ->setParameter(':user_id', $userId)
            ->getQuery()->execute();
    }

    public function removeAllExpired(DateTimeImmutable $now): void
    {
        $this->em->createQueryBuilder()
            ->delete(AuthCode::class, 'ac')
            ->andWhere('ac.expiryDateTime < :date')
            ->setParameter(':date', $now->format(DATE_ATOM))
            ->getQuery()->execute();
    }

    private function exists(string $id): bool
    {
        return $this->repo->createQueryBuilder('t')
            ->select('COUNT(t.identifier)')
            ->andWhere('t.identifier = :identifier')
            ->setParameter(':identifier', $id)
            ->getQuery()->getSingleScalarResult() > 0;
    }
}
