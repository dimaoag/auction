<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use App\Auth\Entity\User\Network;

/**
 * @covers \App\Auth\Entity\User\Network
 *
 * @internal
 */
final class NetworkTest extends TestCase
{
    public function testSuccess(): void
    {
        $network = new Network($name = 'google', $identity = 'google-1');

        self::assertEquals($name, $network->getName());
        self::assertEquals($identity, $network->getIdentity());
    }

    public function testEmptyName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Network($name = '', $identity = 'google-1');
    }

    public function testEmptyIdentity(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Network($name = 'google', $identity = '');
    }

    public function testEqual(): void
    {
        $network = new Network($name = 'google', $identity = 'google-1');

        self::assertTrue($network->isEqualTo(new Network($name, 'google-1')));
        self::assertFalse($network->isEqualTo(new Network($name, 'google-2')));
        self::assertFalse($network->isEqualTo(new Network('vk', 'vk-1')));
    }
}
