<?php

/**
 * File: \src\Validator\Constraints\PasswordComposition.php
 * Author: Peter Nagy <peter@antronin.consulting>
 * -----
 */
declare(strict_types=1);

namespace Antronin\PswCompositionBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Compound;
use Symfony\Component\Validator\Constraints as Assert;
use Antronin\PswCompositionBundle\Validator\Constraints\MinRegex;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class PasswordComposition extends Compound
{
    public function __construct(
        public ?int $minLength,
        public ?int $maxLength,
        public ?int $minLowercase,
        public ?int $minUppercase,
        public ?int $minNumber,
        public ?int $minSpecial,
        public ?string $lowercasePattern,
        public ?string $uppercasePattern,
        public ?string $numberPattern,
        public ?string $specialsPattern,
    ) {
        parent::__construct();
    }

    protected function getConstraints(array $options): array
    {
        $constraints = [
            new Assert\NotBlank(),
            new Assert\Type('string'),
            new Assert\NotCompromisedPassword(),
        ];

        if (isset($this->minLength) || isset($this->maxLength)) {
            if (isset($this->minLength) && !isset($this->maxLength)) {
                $constraints[] = new Assert\Length(min: $this->minLength);
            } elseif (!isset($this->minLength) && isset($this->maxLength)) {
                $constraints[] = new Assert\Length(max: $this->maxLength);
            } else {
                $constraints[] = new Assert\Length(min: $this->minLength, max: $this->maxLength);
            }
        }
        if (isset($this->minLowercase) && $this->minLowercase > 0) {
            $constraints[] = new MinRegex(
                pattern: '/[' . $this->lowercasePattern . ']{' . $this->minLowercase . ',}/u',
                message: 'password.constraints.lowercase',
                min: $this->minLowercase
            );
        }
        if (isset($this->minUppercase) && $this->minUppercase > 0) {
            $constraints[] = new MinRegex(
                pattern: '/[' . $this->uppercasePattern . ']{' . $this->minUppercase . ',}/u',
                message: 'password.constraints.uppercase',
                min: $this->minUppercase
            );
        }
        if (isset($this->minNumber) && $this->minNumber > 0) {
            $constraints[] = new MinRegex(
                pattern: '/[' . $this->numberPattern . ']{' . $this->minNumber . ',}/u',
                message: 'password.constraints.numbers',
                min: $this->minNumber
            );
        }
        if (isset($this->minSpecial) && $this->minSpecial > 0) {
            $constraints[] = new MinRegex(
                pattern: '/[' . preg_quote($this->specialsPattern, '/') . ']{' . $this->minSpecial . ',}/u',
                message: 'password.constraints.specials',
                min: $this->minSpecial
            );
        }

        return $constraints;
    }
}
