<?php

declare(strict_types=1);

namespace Test\Functional\V1\Auth\Join;

use Ramsey\Uuid\Uuid;
use DateTimeImmutable;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\User;
use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Token;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;

final class RequestFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $user = User::requestJoinByEmail(
            Id::generate(),
            $date = new DateTimeImmutable('-30 days'),
            new Email('existing@app.test'),
            'password-hash',
            new Token(Uuid::uuid4()->toString(), $date->modify('+1 day'))
        );

        $manager->persist($user);

        $manager->flush();
    }
}
