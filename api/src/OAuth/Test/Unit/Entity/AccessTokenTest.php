<?php

declare(strict_types=1);

namespace App\OAuth\Test\Unit\Entity;

use function App\env;
use Ramsey\Uuid\Uuid;
use DateTimeImmutable;
use App\OAuth\Entity\Scope;
use PHPUnit\Framework\TestCase;
use App\OAuth\Entity\AccessToken;
use League\OAuth2\Server\CryptKey;
use App\OAuth\Test\Builder\ClientBuilder;

/**
 * @internal
 */
final class AccessTokenTest extends TestCase
{
    public function testCreate(): void
    {
        $token = new AccessToken(
            $client = (new ClientBuilder())->build(),
            $scopes = [new Scope('common')]
        );

        $token->setIdentifier($identifier = Uuid::uuid4()->toString());
        $token->setUserIdentifier($userIdentifier = Uuid::uuid4()->toString());
        $token->setUserRole($userRole = 'admin');
        $token->setExpiryDateTime($expiryDateTime = new DateTimeImmutable());

        $token->setPrivateKey(new CryptKey(env('JWT_PRIVATE_KEY_PATH'), null, false));
        $jwt = $token->convertToJWT();

        self::assertSame($client, $token->getClient());
        self::assertSame($scopes, $token->getScopes());
        self::assertSame($identifier, $token->getIdentifier());
        self::assertSame($userIdentifier, $token->getUserIdentifier());
        self::assertSame($userRole, $token->getUserRole());
        self::assertSame($expiryDateTime, $token->getExpiryDateTime());

        self::assertSame($userIdentifier, $jwt->claims()->get('sub'));
        self::assertSame($userRole, $jwt->claims()->get('role'));
    }
}
