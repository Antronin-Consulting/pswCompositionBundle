<?php

declare(strict_types=1);

namespace AntroninConsulting\PswCompositionBundle\Tests\Validator\Constraints;

use AntroninConsulting\PswCompositionBundle\Validator\Constraints\MinRegex;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Exception\MissingOptionsException;

class MinRegexTest extends TestCase
{
    public function testConstructorWithAllParameters(): void
    {
        $constraint = new MinRegex(
            pattern: '/foo/',
            message: 'my-message',
            min: 5,
            htmlPattern: 'foo',
            match: false,
            normalizer: 'trim',
            groups: ['my_group'],
            payload: ['foo' => 'bar']
        );

        self::assertSame(expected: '/foo/', actual: $constraint->pattern);
        self::assertSame(expected: 'my-message', actual: $constraint->message);
        self::assertSame(expected: 5, actual: $constraint->min);
        self::assertSame(expected: 'foo', actual: $constraint->htmlPattern);
        self::assertFalse(condition: $constraint->match);
        self::assertSame(expected: 'trim', actual: $constraint->normalizer);
        self::assertSame(expected: ['my_group'], actual: $constraint->groups);
        self::assertSame(expected: ['foo' => 'bar'], actual: $constraint->payload);
    }

    public function testConstructorThrowsExceptionWhenPatternIsMissing(): void
    {
        $this->expectException(exception: MissingOptionsException::class);
        $this->expectExceptionMessage(message: 'The options "pattern" must be set for constraint "AntroninConsulting\PswCompositionBundle\Validator\Constraints\MinRegex".');

        new MinRegex(pattern: null);
    }

    public function testMinIsSetToZeroWhenNull(): void
    {
        $constraint = new MinRegex(pattern: '/foo/', min: null);

        self::assertSame(expected: 0, actual: $constraint->min);
    }
}
