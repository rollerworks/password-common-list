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

use Rollerworks\Component\PasswordCommonList\Constraints\NotInPasswordCommonList;
use Rollerworks\Component\PasswordCommonList\Constraints\NotInPasswordCommonListValidator;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * @internal
 *
 * @template-extends ConstraintValidatorTestCase<NotInPasswordCommonListValidator>
 */
final class NotInPasswordCommonListTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): ConstraintValidatorInterface
    {
        return new NotInPasswordCommonListValidator();
    }

    /** @test */
    public function it_ignores_empty_values(): void
    {
        $this->validator->validate(null, new NotInPasswordCommonList());
        $this->assertNoViolation();

        $this->validator->validate('', new NotInPasswordCommonList());
        $this->assertNoViolation();

        $this->validator->validate(
            new class() implements \Stringable {
                public function __toString(): string
                {
                    return '';
                }
            },
            new NotInPasswordCommonList()
        );
        $this->assertNoViolation();
    }

    /** @test */
    public function no_violation_for_unlisted_password(): void
    {
        $this->validator->validate('#*Xqz%<*8wHi', new NotInPasswordCommonList());
        $this->assertNoViolation();

        $this->validator->validate('nope', new NotInPasswordCommonList());
        $this->assertNoViolation();
    }

    /**
     * @test
     *
     * @dataProvider provide_unsafe_passwords
     */
    public function it_raises_a_violation_for_common_used_password_as_string(string $password): void
    {
        $this->validator->validate($password, $constraint = new NotInPasswordCommonList());

        $this->buildViolation($constraint->message)
            ->setInvalidValue($password)
            ->assertRaised()
        ;
    }

    /**
     * @test
     *
     * @dataProvider provide_unsafe_passwords
     */
    public function it_raises_a_violation_for_common_used_password_as_stringable(string $password): void
    {
        $value = new class($password) implements \Stringable {
            private string $password;

            public function __construct(string $password)
            {
                $this->password = $password;
            }

            public function __toString(): string
            {
                return $this->password;
            }
        };

        $this->validator->validate($value, $constraint = new NotInPasswordCommonList());

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
