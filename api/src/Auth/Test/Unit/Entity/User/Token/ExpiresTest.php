<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User\Token;

use Ramsey\Uuid\Uuid;
use DateTimeImmutable;
use App\Auth\Entity\User\Token;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Auth\Entity\User\Token::isExpiredTo
 *
 * @internal
 */
final class ExpiresTest extends TestCase
{
    public function testNot(): void
    {
        $token = new Token(
            Uuid::uuid4()->toString(),
            $expires = new DateTimeImmutable()
        );

        self::assertFalse($token->isExpiredTo($expires->modify('-1 secs')));
        self::assertTrue($token->isExpiredTo($expires));
        self::assertTrue($token->isExpiredTo($expires->modify('+1 secs')));
    }
}
