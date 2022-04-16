<?php

declare(strict_types=1);

namespace Test\Functional;

use function App\env;
use DateTimeImmutable;
use App\OAuth\Entity\Scope;
use App\OAuth\Entity\Client;
use App\OAuth\Generator\Params;
use App\OAuth\Generator\AccessTokenGenerator;

final class AuthHeader
{
    public static function for(string $userId, string $role): string
    {
        $generator = new AccessTokenGenerator(env('JWT_PRIVATE_KEY_PATH'));

        $token = $generator->generate(
            new Client(
                identifier: 'frontend',
                name: 'Auction',
                redirectUri: 'http://localhost/oauth'
            ),
            [new Scope('common')],
            new Params(
                userId: $userId,
                role: $role,
                expires: new DateTimeImmutable('+1000 years'),
            )
        );

        return 'Bearer ' . $token;
    }
}
