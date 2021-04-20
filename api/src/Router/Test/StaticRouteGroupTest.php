<?php

declare(strict_types=1);

namespace App\Router\Test;

use PHPUnit\Framework\TestCase;
use App\Router\StaticRouteGroup;
use Slim\Routing\RouteCollectorProxy;

/**
 * @internal
 */
final class StaticRouteGroupTest extends TestCase
{
    public function testSuccess(): void
    {
        $collector = $this->createStub(RouteCollectorProxy::class);

        $callable = static fn (RouteCollectorProxy $collector): RouteCollectorProxy => $collector;

        $group = new StaticRouteGroup($callable);

        self::assertSame($collector, $group($collector));
    }
}
