<?php

declare(strict_types=1);

namespace Antronin\PswCompositionBundle\Tests\Validator\Constraints;

use Antronin\PswCompositionBundle\Validator\Constraints\MinRegex;
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

        self::assertSame('/foo/', $constraint->pattern);
        self::assertSame('my-message', $constraint->message);
        self::assertSame(5, $constraint->min);
        self::assertSame('foo', $constraint->htmlPattern);
        self::assertFalse($constraint->match);
        self::assertSame('trim', $constraint->normalizer);
        self::assertSame(['my_group'], $constraint->groups);
        self::assertSame(['foo' => 'bar'], $constraint->payload);
    }

    public function testConstructorThrowsExceptionWhenPatternIsMissing(): void
    {
        $this->expectException(MissingOptionsException::class);
        $this->expectExceptionMessage('The options "pattern" must be set for constraint "Antronin\PswCompositionBundle\Validator\Constraints\MinRegex".');

        new MinRegex(pattern: null);
    }

    public function testMinIsSetToZeroWhenNull(): void
    {
        $constraint = new MinRegex(pattern: '/foo/', min: null);

        self::assertSame(0, $constraint->min);
    }
}
