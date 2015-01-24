# BE Bank Transfer Message

[![Latest Version](https://img.shields.io/github/release/thephpleague/:package_name.svg?style=flat-square)](https://github.com/thephpleague/:package_name/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/thephpleague/:package_name/master.svg?style=flat-square)](https://travis-ci.org/thephpleague/:package_name)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/thephpleague/:package_name.svg?style=flat-square)](https://scrutinizer-ci.com/g/thephpleague/:package_name/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/thephpleague/:package_name.svg?style=flat-square)](https://scrutinizer-ci.com/g/thephpleague/:package_name)
[![Total Downloads](https://img.shields.io/packagist/dt/league/:package_name.svg?style=flat-square)](https://packagist.org/packages/league/:package_name)

This package contains a validator and generator for structured messages included in Belgian bank transfers.

## Install

Via Composer

``` bash
$ composer require netsensei/be-bank-transfer-message
```

## Usage

### Generate a structured message

Generate a structured message based on a predefined number. If you don't pass a number to the constructor, a random number will be generated.

``` php
$transferMessage = new Netsensei\BeBankTransferMessage\TransferMessage(12345);
$transferMessage->generate();
echo transferMessage->getStructuredMessage();
```

Set a new number on a generate again. If you don't pass a new number, a random number will be generated.

``` php
$transferMessage->setNumber(54321)
$transferMessage->generate();
echo transferMessage->getStructuredMessage();
```

## Testing

``` bash
$ phpunit
```

## Contributing

Please see [CONTRIBUTING](https://github.com/thephpleague/:package_name/blob/master/CONTRIBUTING.md) for details.

## Credits

- [Matthias Vandermaesen](https://github.com/netsensei)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
