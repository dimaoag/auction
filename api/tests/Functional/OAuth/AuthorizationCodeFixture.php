<?php

declare(strict_types=1);

namespace Test\Functional\OAuth;

use DateTimeImmutable;
use App\OAuth\Entity\Scope;
use App\Auth\Entity\User\Id;
use App\OAuth\Entity\Client;
use App\OAuth\Entity\AuthCode;
use App\Auth\Test\Builder\UserBuilder;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;

final class AuthorizationCodeFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $user = (new UserBuilder())
            ->withId(new Id('00000000-0000-0000-0000-000000000001'))
            ->active()
            ->build();

        $manager->persist($user);

        $code = new AuthCode();
        $code->setClient(new Client(
            identifier: 'frontend',
            name: 'Frontend',
            redirectUri: 'http://localhost:8080/oauth',
        ));
        $code->addScope(new Scope('common'));
        $code->setExpiryDateTime(new DateTimeImmutable('2300-12-31 21:00:10'));
        $code->setIdentifier('def50200f204dedbb244ce4539b9e');
        $code->setUserIdentifier($user->getId()->getValue());
        $code->setRedirectUri('http://localhost:8080/oauth');

        $manager->persist($code);

        $manager->flush();
    }
}
