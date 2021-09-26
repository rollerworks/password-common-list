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

namespace Rollerworks\Component\PasswordCommonList\Tests\Constraints;

use Rollerworks\Component\PasswordCommonList\Constraints\PasswordCommonList;
use Rollerworks\Component\PasswordCommonList\Constraints\PasswordCommonListValidator;
use Stringable;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * @internal
 */
final class PasswordCommonListValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): PasswordCommonListValidator
    {
        return new PasswordCommonListValidator();
    }

    /** @test */
    public function it_ignores_empty_values(): void
    {
        $this->validator->validate(null, new PasswordCommonList());
        $this->assertNoViolation();

        $this->validator->validate('', new PasswordCommonList());
        $this->assertNoViolation();

        $this->validator->validate(
            new class() implements Stringable {
                public function __toString()
                {
                    return '';
                }
            },
            new PasswordCommonList()
        );
        $this->assertNoViolation();
    }

    /** @test */
    public function no_violation_for_unlisted_password(): void
    {
        $this->validator->validate('#*Xqz%<*8wHi', new PasswordCommonList());
        $this->assertNoViolation();

        $this->validator->validate('nope', new PasswordCommonList());
        $this->assertNoViolation();
    }

    /**
     * @test
     * @dataProvider provide_unsafe_passwords
     */
    public function it_raises_a_violation_for_common_used_password_as_string(string $password): void
    {
        $this->validator->validate($password, $constraint = new PasswordCommonList());

        $this->buildViolation($constraint->message)
            ->setInvalidValue($password)
            ->assertRaised()
        ;
    }

    /**
     * @test
     * @dataProvider provide_unsafe_passwords
     */
    public function it_raises_a_violation_for_common_used_password_as_stringable(string $password): void
    {
        $value = new class($password) implements Stringable {
            private string $password;

            public function __construct(string $password)
            {
                $this->password = $password;
            }

            public function __toString()
            {
                return $this->password;
            }
        };

        $this->validator->validate($value, $constraint = new PasswordCommonList());

        $this->buildViolation($constraint->message)
            ->setInvalidValue($password)
            ->assertRaised()
        ;
    }

    public function provide_unsafe_passwords(): iterable
    {
        yield ['hunter'];
        yield ['123qwe123'];
        yield ['passwords'];
        yield ['werhnbkliopfdsrftgwerhnbkliopfdsrftg'];
    }
}
