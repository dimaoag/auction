<?php

declare(strict_types=1);

namespace App\FeatureToggle\Test;

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

        $result = $processor([
            'message' => 'Message',
        ]);

        self::assertEquals([
            'message' => 'Message',
            'extra' => [
                'features' => $source,
            ],
        ], $result);
    }
}
