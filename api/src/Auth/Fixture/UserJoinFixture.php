<?php

declare(strict_types=1);

namespace App\Auth\Fixture;

use DateTimeImmutable;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\User;
use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Token;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;

final class UserJoinFixture extends AbstractFixture
{
    private const PASSWORD_HASH = '$2y$12$qwnND33o8DGWvFoepotSju7eTAQ6gzLD/zy6W8NCVtiHPbkybz.w6';

    public function load(ObjectManager $manager): void
    {
        $user = User::requestJoinByEmail(
            Id::generate(),
            new DateTimeImmutable('-1 hours'),
            new Email('join-existing@app.test'),
            self::PASSWORD_HASH,
            new Token('00000000-0000-0000-0000-100000000001', new DateTimeImmutable('+1 hours'))
        );
        $manager->persist($user);

        $manager->flush();
    }
}
