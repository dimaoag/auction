<?php

declare(strict_types=1);

namespace Test\Functional\OAuth;

use DateTimeImmutable;
use App\Auth\Entity\User\Id;
use App\OAuth\Entity\Client;
use App\OAuth\Entity\RefreshToken;
use App\Auth\Test\Builder\UserBuilder;
use Doctrine\Persistence\ObjectManager;
use App\OAuth\Test\Builder\AccessTokenBuilder;
use Doctrine\Common\DataFixtures\AbstractFixture;

final class RefreshTokenFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $user = (new UserBuilder())
            ->withId(new Id($id = '00000000-0000-0000-0000-000000000001'))
            ->active()
            ->build();

        $manager->persist($user);

        $client = new Client(
            identifier: 'frontend',
            name: 'Frontend',
            redirectUri: 'http://localhost:8080/oauth',
        );

        $accessToken = (new AccessTokenBuilder())
            ->withUserIdentifier($id)
            ->build($client);

        $refreshToken = new RefreshToken();
        $refreshToken->setAccessToken($accessToken);
        $refreshToken->setExpiryDateTime(new DateTimeImmutable('2300-12-31 21:00:10'));
        $refreshToken->setIdentifier('aef50200f204dedbb244ce4539b9e');

        $manager->persist($refreshToken);

        $manager->flush();
    }
}
