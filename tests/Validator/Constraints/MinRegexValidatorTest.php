<?php

declare(strict_types=1);

namespace Antronin\PswCompositionBundle\Tests\Validator\Constraints;

use Antronin\PswCompositionBundle\Validator\Constraints\MinRegex;
use Antronin\PswCompositionBundle\Validator\Constraints\MinRegexValidator;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class MinRegexValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): MinRegexValidator
    {
        return new MinRegexValidator();
    }

    public function testNullIsValid(): void
    {
        $this->validator->validate(null, new MinRegex(pattern: '/foo/'));
        $this->assertNoViolation();
    }

    public function testEmptyStringIsValid(): void
    {
        $this->validator->validate('', new MinRegex(pattern: '/foo/'));
        $this->assertNoViolation();
    }

    public function testExpectsStringCompatibleType(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->validator->validate(new \stdClass(), new MinRegex(pattern: '/foo/'));
    }

    public function testInvalidConstraintThrowsException(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->validator->validate('foo', new class() extends Constraint {});
    }

    public static function getValidValues(): iterable
    {
        yield ['a', '/a/', 1];
        yield ['aa', '/a/', 2];
        yield ['abc', '/[a-z]/', 3];
        yield ['de', '/[a-z]/', 2];
        yield [new StringableValue('a'), '/a/', 1];
    }

    #[DataProvider('getValidValues')]
    public function testValidValues(mixed $value, string $pattern, int $min): void
    {
        $constraint = new MinRegex(pattern: $pattern, min: $min);
        $this->validator->validate($value, $constraint);

        $this->assertNoViolation();
    }

    public static function getInvalidValues(): iterable
    {
        yield ['a', 'my-message', '/b/', 1];
        yield ['b', 'my-message', '/c/', 1];
        yield ['abc', 'my-message', '/d/', 1];
        yield ['foobar', 'my-message', '/f/', 2];
        yield [new StringableValue('a'), 'my-message', '/b/', 5];
    }

    #[DataProvider('getInvalidValues')]
    public function testInvalidValues(mixed $value, string $message, string $pattern, int $min): void
    {
        $constraint = new MinRegex(
            message: $message,
            pattern: $pattern,
            min: $min
        );

        $this->validator->validate($value, $constraint);

        $this->buildViolation($message)
            ->setParameter('{{ value }}', '"' . (string) $value . '"')
            ->setParameter('{{ pattern }}', $pattern)
            ->setParameter('{{ min }}', (string) $min)
            ->setCode(MinRegex::MIN_REGEX_FAILED_ERROR)
            ->assertRaised();
    }

    public function testNormalizerIsCalled(): void
    {
        $constraint = new MinRegex(
            pattern: '/f/',
            min: 1,
            normalizer: 'trim'
        );

        $this->validator->validate(' foo ', $constraint);

        $this->assertNoViolation();
    }

    public function testNormalizerIsCalledOnInvalidValue(): void
    {
        $constraint = new MinRegex(
            pattern: '/f/',
            normalizer: 'trim',
            message: 'my-message',
            min: 1
        );

        $this->validator->validate(' bar ', $constraint);

        $this->buildViolation('my-message')
            ->setParameter('{{ value }}', '"bar"')
            ->setParameter('{{ pattern }}', '/f/')
            ->setParameter('{{ min }}', '1')
            ->setCode(MinRegex::MIN_REGEX_FAILED_ERROR)
            ->assertRaised();
    }
}

class StringableValue
{
    public function __construct(
        private readonly string $value
    ) {}

    public function __toString(): string
    {
        return $this->value;
    }
}
