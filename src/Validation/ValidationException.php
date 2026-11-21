<?php

declare(strict_types=1);

namespace App\Validation;

use RuntimeException;

final class ValidationException extends RuntimeException
{
    /**
     * @param array<string, string> $errors Field name => message.
     * @param array<string, string> $input  The submitted values, for re-filling the form.
     */
    public function __construct(
        public readonly array $errors,
        public readonly array $input,
    ) {
        parent::__construct('The submitted contact is invalid.');
    }
}
