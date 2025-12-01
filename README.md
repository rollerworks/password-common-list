PasswordCommonList Validator
============================

This package provides a [Symfony Validator] for the [xato-net-10-million-passwords-1000000] CommonPassword list.

**Note:** It's better to use the [NotCompromisedPassword](https://symfony.com/doc/current/reference/constraints/NotCompromisedPassword.html#notcompromisedpassword)
validator when possible. The PasswordCommonList should only be used when network access is limited or restricted.

**Only passwords of 6 or more characters are included in this list.**

## Installation

To install this package, add `rollerworks/password-common-list` to your composer.json:

```bash
$ php composer.phar require rollerworks/password-common-list
```

Now, [Composer][composer] will automatically download all required files,
and install them for you.

**Note:** To use this library with a Symfony Application make sure the
`\Rollerworks\Bundle\PasswordCommonListBundle\RollerworksPasswordCommonListBundle` is enabled.

## Requirements

You need at least PHP 8.0 and Symfony 6.4, mbstring is recommended but not required.

## Usage

**Caution:**

> The password validators do not enforce that the field must have a value!
> To make a field "required" use the [NotBlank constraint](http://symfony.com/doc/current/reference/constraints/NotBlank.html)
> in combination with the PasswordCommonList validator.

Use the `Rollerworks\Component\PasswordCommonList\Validator\Constraints\NotInPasswordCommonList` constraint as
described in the [Symfony Documentation](https://symfony.com/doc/current/validation.html). This constraint has no special options.

## Versioning

For transparency and insight into the release cycle, and for striving
to maintain backward compatibility, this package is maintained under
the Semantic Versioning guidelines as much as possible.

Releases will be numbered with the following format:

`<major>.<minor>.<patch>`

And constructed with the following guidelines:

* Breaking backward compatibility bumps the major (and resets the minor and patch)
* New additions without breaking backward compatibility bumps the minor (and resets the patch)
* Bug fixes and misc changes bumps the patch

For more information on SemVer, please visit <http://semver.org/>.

## License

This library is released under the [MIT license](LICENSE).

## Contributing

This is an open source project. If you'd like to contribute,
please read the [Contributing Guidelines][contributing]. If you're submitting
a pull request, please follow the guidelines in the [Submitting a Patch][patches] section.

[Symfony Validator]: (http://symfony.com/doc/current/components/validator.html
[xato-net-10-million-passwords-1000000]: https://github.com/danielmiessler/SecLists/tree/master/Passwords
[composer]: https://getcomposer.org/doc/00-intro.md
[flex]: https://symfony.com/doc/current/setup/flex.html
[contributing]: https://contributing.rollerscapes.net/
[patches]: https://contributing.rollerscapes.net/latest/patches.html
