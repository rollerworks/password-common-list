<?php

declare(strict_types=1);

/*
 * This file is part of the RollerworksPasswordCommonList package.
 *
 * (c) Sebastiaan Stok <s.stok@rollerscapes.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Rollerworks\Component\PasswordCommonList\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class PasswordCommonListValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (! $constraint instanceof PasswordCommonList) {
            throw new UnexpectedTypeException($constraint, PasswordCommonList::class);
        }

        if ($value === null || $value === '') {
            return;
        }

        if (! is_scalar($value) && ! (\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedValueException($value, 'string');
        }

        $inputValue = (string) $value;
        $value = $inputValue;

        $passwordFile = __DIR__ . '/../../Resources/common_lists/list-' . mb_strlen($value) . '.php';

        if (! file_exists($passwordFile)) {
            return;
        }

        $commonPasswords = require $passwordFile;

        if (isset($commonPasswords[mb_strtolower($value)])) {
            $this->context->buildViolation($constraint->message)
                ->setInvalidValue($value)
                ->addViolation()
            ;
        }
    }
}
