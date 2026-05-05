<?php

declare(strict_types=1);

namespace AntroninConsulting\PswCompositionBundle\Tests\Validator\Constraints;

use AntroninConsulting\PswCompositionBundle\Validator\Constraints\MinRegex;
use AntroninConsulting\PswCompositionBundle\Validator\Constraints\PasswordComposition;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

class PasswordCompositionTest extends TestCase
{
    public function testGetConstraintsIncludesBaseConstraints(): void
    {
        $constraint = new PasswordComposition();
        $innerConstraints = $constraint->getNestedConstraints();

        $this->assertContainsInstanceOf(class: Assert\NotBlank::class, constraints: $innerConstraints);
        $this->assertContainsInstanceOf(class: Assert\Type::class, constraints: $innerConstraints);
        $this->assertContainsInstanceOf(class: Assert\NotCompromisedPassword::class, constraints: $innerConstraints);
    }

    public function testGetConstraintsWithLength(): void
    {
        $constraint = new PasswordComposition(lengthEnabled: true, minLength: 8, maxLength: 64);
        $innerConstraints = $constraint->getNestedConstraints();
        $lengthConstraint = $this->findConstraint(class: Assert\Length::class, constraints: $innerConstraints);

        self::assertNotNull(actual: $lengthConstraint);
        self::assertSame(expected: 8, actual: $lengthConstraint->min);
        self::assertSame(expected: 64, actual: $lengthConstraint->max);
    }

    public function testGetConstraintsWithMinLengthOnly(): void
    {
        $constraint = new PasswordComposition(lengthEnabled: true, minLength: 10);
        $innerConstraints = $constraint->getNestedConstraints();
        $lengthConstraint = $this->findConstraint(class: Assert\Length::class, constraints: $innerConstraints);

        self::assertNotNull(actual: $lengthConstraint);
        self::assertSame(expected: 10, actual: $lengthConstraint->min);
        self::assertNull(actual: $lengthConstraint->max);
    }

    public function testGetConstraintsWithMaxLengthOnly(): void
    {
        $constraint = new PasswordComposition(lengthEnabled: true, maxLength: 128);
        $innerConstraints = $constraint->getNestedConstraints();
        $lengthConstraint = $this->findConstraint(class: Assert\Length::class, constraints: $innerConstraints);

        self::assertNotNull(actual: $lengthConstraint);
        self::assertNull(actual: $lengthConstraint->min);
        self::assertSame(expected: 128, actual: $lengthConstraint->max);
    }

    public function testGetConstraintsWithMinLowercase(): void
    {
        $constraint = new PasswordComposition(lowercaseEnabled: true, minLowercase: 2, lowercasePattern: 'a-z');
        $innerConstraints = $constraint->getNestedConstraints();
        $minRegex = $this->findConstraint(class: MinRegex::class, constraints: $innerConstraints);

        self::assertNotNull(actual: $minRegex);
        self::assertSame(expected: '/[a-z]{2,}/u', actual: $minRegex->pattern);
        self::assertSame(expected: 2, actual: $minRegex->min);
        self::assertSame(expected: 'password.constraints.lowercase', actual: $minRegex->message);
    }

    public function testGetConstraintsWithMinUppercase(): void
    {
        $constraint = new PasswordComposition(uppercaseEnabled: true, minUppercase: 2, uppercasePattern: 'A-Z');
        $innerConstraints = $constraint->getNestedConstraints();
        $minRegex = $this->findConstraint(class: MinRegex::class, constraints: $innerConstraints);

        self::assertNotNull(actual: $minRegex);
        self::assertSame(expected: '/[A-Z]{2,}/u', actual: $minRegex->pattern);
        self::assertSame(expected: 2, actual: $minRegex->min);
        self::assertSame(expected: 'password.constraints.uppercase', actual: $minRegex->message);
    }

    public function testGetConstraintsWithMinNumber(): void
    {
        $constraint = new PasswordComposition(numberEnabled: true, minNumber: 2, numberPattern: '0-9');
        $innerConstraints = $constraint->getNestedConstraints();
        $minRegex = $this->findConstraint(class: MinRegex::class, constraints: $innerConstraints);

        self::assertNotNull(actual: $minRegex);
        self::assertSame(expected: '/[0-9]{2,}/u', actual: $minRegex->pattern);
        self::assertSame(expected: 2, actual: $minRegex->min);
        self::assertSame(expected: 'password.constraints.numbers', actual: $minRegex->message);
    }

    public function testGetConstraintsWithMinSpecials(): void
    {
        $constraint = new PasswordComposition(specialEnabled: true, minSpecial: 2, specialsPattern: '!@#$%^&*()-+');
        $innerConstraints = $constraint->getNestedConstraints();
        $minRegex = $this->findConstraint(class: MinRegex::class, constraints: $innerConstraints);

        self::assertNotNull(actual: $minRegex);
        self::assertSame(expected: '/[\!@\#\$%\^&\*\(\)\-\+]{2,}/u', actual: $minRegex->pattern);
        self::assertSame(expected: 2, actual: $minRegex->min);
        self::assertSame(expected: 'password.constraints.specials', actual: $minRegex->message);
    }

    public function testGetConstraintsWithAllOptions(): void
    {
        $constraint = new PasswordComposition(
            lengthEnabled: true,
            minLength: 12,
            maxLength: 100,
            lowercaseEnabled: true,
            minLowercase: 2,
            uppercaseEnabled: true,
            minUppercase: 3,
            numberEnabled: true,
            minNumber: 4,
            specialEnabled: true,
            minSpecial: 1,
            lowercasePattern: 'a-z',
            uppercasePattern: 'A-Z',
            numberPattern: '0-9',
            specialsPattern: '!@#$'
        );

        $innerConstraints = $constraint->getNestedConstraints();

        self::assertCount(expectedCount: 8, haystack: $innerConstraints); // NotBlank, Type, NotCompromised, Length, and 4 MinRegex

        $lengthConstraint = $this->findConstraint(class: Assert\Length::class, constraints: $innerConstraints);
        self::assertNotNull(actual: $lengthConstraint);
        self::assertSame(expected: 12, actual: $lengthConstraint->min);
        self::assertSame(expected: 100, actual: $lengthConstraint->max);

        $regexConstraints = array_values(array: array_filter(array: $innerConstraints, callback: static fn(Constraint $c): bool => $c instanceof MinRegex));

        self::assertCount(expectedCount: 4, haystack: $regexConstraints);

        self::assertSame(expected: '/[a-z]{2,}/u', actual: $regexConstraints[0]->pattern);
        self::assertSame(expected: 2, actual: $regexConstraints[0]->min);

        self::assertSame(expected: '/[A-Z]{3,}/u', actual: $regexConstraints[1]->pattern);
        self::assertSame(expected: 3, actual: $regexConstraints[1]->min);

        self::assertSame(expected: '/[0-9]{4,}/u', actual: $regexConstraints[2]->pattern);
        self::assertSame(expected: 4, actual: $regexConstraints[2]->min);

        self::assertSame(expected: '/[' . preg_quote(str: '!@#$', delimiter: '/') . ']{1,}/u', actual: $regexConstraints[3]->pattern);
        self::assertSame(expected: 1, actual: $regexConstraints[3]->min);
    }

    /**
     * @param object[] $constraints
     */
    private function assertContainsInstanceOf(string $class, array $constraints): void
    {
        $found = (bool) $this->findConstraint(class: $class, constraints: $constraints);
        self::assertTrue(condition: $found, message: "Failed asserting that an instance of '$class' is in the constraint list.");
    }

    /**
     * @param string $class
     * @param object[] $constraints
     * @return object|null
     */
    private function findConstraint(string $class, array $constraints): ?object
    {
        foreach ($constraints as $constraint) {
            if ($constraint instanceof $class) {
                return $constraint;
            }
        }
        return null;
    }
}
