<?php

/**
 * File: \src\Validator\Constraints\PasswordComposition.php
 * Author: Peter Nagy <peter@antronin.consulting>
 * -----
 */

declare(strict_types=1);

namespace AntroninConsulting\PswCompositionBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Compound;
use Symfony\Component\Validator\Constraints as Assert;
use AntroninConsulting\PswCompositionBundle\Validator\Constraints\MinRegex;

#[\Attribute(flags: \Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class PasswordComposition extends Compound
{
    public function __construct(
        public bool $lengthEnabled = false,
        public ?int $minLength = null,
        public ?int $maxLength = null,
        public bool $lowercaseEnabled = false,
        public bool $uppercaseEnabled = false,
        public bool $numberEnabled = false,
        public bool $specialEnabled = false,
        public ?int $minLowercase = null,
        public ?int $minUppercase = null,
        public ?int $minNumber = null,
        public ?int $minSpecial = null,
        public ?string $lowercasePattern = null,
        public ?string $uppercasePattern = null,
        public ?string $numberPattern = null,
        public ?string $specialsPattern = null,
    ) {
        parent::__construct();
    }

    protected function getConstraints(array $options): array
    {
        $constraints = [
            new Assert\NotBlank(),
            new Assert\Type(type: 'string'),
            new Assert\NotCompromisedPassword(),
        ];

        if ($this->lengthEnabled) {
            if (isset($this->minLength) && !isset($this->maxLength)) {
                $constraints[] = new Assert\Length(min: $this->minLength);
            } elseif (!isset($this->minLength) && isset($this->maxLength)) {
                $constraints[] = new Assert\Length(max: $this->maxLength);
            } else {
                $constraints[] = new Assert\Length(min: $this->minLength, max: $this->maxLength);
            }
        }
        if ($this->lowercaseEnabled && isset($this->minLowercase) && $this->minLowercase > 0) {
            $constraints[] = new MinRegex(
                pattern: '/[' . $this->lowercasePattern . ']{' . $this->minLowercase . ',}/u',
                message: 'password.constraints.lowercase',
                min: $this->minLowercase
            );
        }
        if ($this->uppercaseEnabled && isset($this->minUppercase) && $this->minUppercase > 0) {
            $constraints[] = new MinRegex(
                pattern: '/[' . $this->uppercasePattern . ']{' . $this->minUppercase . ',}/u',
                message: 'password.constraints.uppercase',
                min: $this->minUppercase
            );
        }
        if ($this->numberEnabled && isset($this->minNumber) && $this->minNumber > 0) {
            $constraints[] = new MinRegex(
                pattern: '/[' . $this->numberPattern . ']{' . $this->minNumber . ',}/u',
                message: 'password.constraints.numbers',
                min: $this->minNumber
            );
        }
        if ($this->specialEnabled && isset($this->minSpecial) && $this->minSpecial > 0) {
            $constraints[] = new MinRegex(
                pattern: '/[' . preg_quote($this->specialsPattern, '/') . ']{' . $this->minSpecial . ',}/u',
                message: 'password.constraints.specials',
                min: $this->minSpecial
            );
        }

        return $constraints;
    }
}
