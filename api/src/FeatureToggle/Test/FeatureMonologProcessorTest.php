<?php

declare(strict_types=1);

namespace App\FeatureToggle\Test;

use Monolog\Logger;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use App\FeatureToggle\FeaturesContext;
use App\FeatureToggle\FeaturesMonologProcessor;

/**
 * @internal
 */
final class FeatureMonologProcessorTest extends TestCase
{
    public function testProcess(): void
    {
        $context = $this->createStub(FeaturesContext::class);
        $context->method('getAllEnabled')->willReturn($source = ['ONE', 'TWO']);

        $processor = new FeaturesMonologProcessor($context);

        $date = new DateTimeImmutable();

        $result = $processor([
            'message' => 'Message',
            'context' => ['name' => 'value'],
            'level' => Logger::WARNING,
            'level_name' => 'WARNING',
            'channel' => 'channel',
            'datetime' => $date,
            'extra' => ['param' => 'value'],
        ]);

        self::assertEquals([
            'message' => 'Message',
            'context' => ['name' => 'value'],
            'level' => Logger::WARNING,
            'level_name' => 'WARNING',
            'channel' => 'channel',
            'datetime' => $date,
            'extra' => [
                'param' => 'value',
                'features' => $source,
            ],
        ], $result);
    }
}
