<?php

/**
 * File: \src\Validator\Constraints\MinRegexValidator.php
 * Author: Peter Nagy <peter@antronin.consulting>
 * -----
 */

declare(strict_types=1);

namespace AntroninConsulting\PswCompositionBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\RegexValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class MinRegexValidator extends RegexValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof MinRegex) {
            throw new UnexpectedTypeException($constraint, MinRegex::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!\is_scalar($value) && !$value instanceof \Stringable) {
            throw new UnexpectedValueException($value, 'string');
        }

        $value = (string) $value;

        if (null !== $constraint->normalizer) {
            $value = ($constraint->normalizer)($value);
        }

        if (preg_match_all($constraint->pattern, $value) < $constraint->min) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setParameter('{{ pattern }}', $constraint->pattern)
                ->setParameter('{{ min }}', (string) $constraint->min)
                ->setCode(MinRegex::MIN_REGEX_FAILED_ERROR)
                ->addViolation();
        }
    }
}
