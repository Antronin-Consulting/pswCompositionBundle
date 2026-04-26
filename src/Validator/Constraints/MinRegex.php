<?php

declare(strict_types=1);
/**
 * File: \src\Validator\Constraints\MinRegex.php
 * Author: Peter Nagy <peter@antronin.consulting>
 * -----
 */

namespace AntroninConsulting\PswCompositionBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Exception\MissingOptionsException;

/**
 * Validates that a value matches a regular expression.
 *
 * @author Peter Nagy <peter@antronin.consulting>
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class MinRegex extends Regex
{
    public const MIN_REGEX_FAILED_ERROR = '6cdafa98-0d64-477a-aaa9-e281d6db07fc';
    public ?int $min;

    /**
     * @param int|null      $min         The minimum number of the character class
     */
    public function __construct(
        ?string $pattern,
        ?string $message = null,
        ?int $min = null,
        ?string $htmlPattern = null,
        ?bool $match = null,
        ?callable $normalizer = null,
        ?array $groups = null,
        mixed $payload = null,
    ) {
        if (null === $pattern) {
            throw new MissingOptionsException(\sprintf('The options "pattern" must be set for constraint "%s".', self::class), ['pattern']);
        }
        $this->min = $min ?? 0;
        parent::__construct(
            pattern: $pattern,
            message: $message,
            htmlPattern: $htmlPattern,
            match: $match,
            normalizer: $normalizer,
            groups: $groups,
            payload: $payload
        );
    }
}
