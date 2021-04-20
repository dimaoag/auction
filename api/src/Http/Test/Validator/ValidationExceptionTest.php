<?php

declare(strict_types=1);

namespace App\Http\Test\Validator;

use PHPUnit\Framework\TestCase;
use App\Http\Validator\ValidationException;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * @covers \App\Http\Validator\ValidationException
 *
 * @internal
 */
final class ValidationExceptionTest extends TestCase
{
    public function testValid(): void
    {
        $exception = new ValidationException(
            $violations = new ConstraintViolationList()
        );

        self::assertEquals('Invalid input.', $exception->getMessage());
        self::assertEquals($violations, $exception->getViolations());
    }
}
