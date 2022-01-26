<?php

declare(strict_types=1);

namespace App\OAuth\Test\Unit\Entity;

use Ramsey\Uuid\Uuid;
use App\OAuth\Entity\User;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class UserTest extends TestCase
{
    public function testCreate(): void
    {
        $user = new User($identifier = Uuid::uuid4()->toString());

        self::assertSame($identifier, $user->getIdentifier());
    }
}
