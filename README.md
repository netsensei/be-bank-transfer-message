# BE Bank Transfer Message

[![Latest Version](https://img.shields.io/github/release/kouinkouin/be-bank-transfer-message.svg?style=flat-square)](https://github.com/kouinkouin/be-bank-transfer-message/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/kouinkouin/be-bank-transfer-message/master.svg?style=flat-square)](https://travis-ci.org/kouinkouin/be-bank-transfer-message)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/kouinkouin/be-bank-transfer-message.svg?style=flat-square)](https://scrutinizer-ci.com/g/kouinkouin/be-bank-transfer-message/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/kouinkouin/be-bank-transfer-message.svg?style=flat-square)](https://scrutinizer-ci.com/g/kouinkouin/be-bank-transfer-message)
[![Total Downloads](https://img.shields.io/packagist/dt/kouinkouin/be-bank-transfer-message.svg?style=flat-square)](https://packagist.org/packages/kouinkouin/be-bank-transfer-message)

This package contains a validator and generator for structured messages included in Belgian bank transfers. Common use cases:

* Automatic generation of order invoices.
* Association of payment provider messages to orders stored in your application.

The structured message format adheres to the [Febelfin Guidelines](https://www.febelfin.be/sites/default/files/files/dw-formulier_euro2.pdf)

## Install

Via Composer

``` bash
$ composer require kouinkouin/be-bank-transfer-message
```

## Usage

### Generate a structured message

Based on a random number

``` php
$transferMessage = new BeBankTransferMessage\TransferMessage();
echo transferMessage->getStructuredMessage();
```

Based on a predefined number

``` php
$transferMessage = new BeBankTransferMessage\TransferMessage(12345);
echo transferMessage->getStructuredMessage();
```

Change to a different predefined number

``` php
$transferMessage->setNumber(54321);
$transferMessage->generate();
echo transferMessage->getStructuredMessage();
```

Or a random number

``` php
$transferMessage->setNumber();
$transferMessage->generate();
echo transferMessage->getStructuredMessage();
```

The default, valid circumfix of a structured message is the plus sign. Optionally, it's possible to use asterisks as a circumfix, if your formatting demands it.

``` php
$transferMessage->setNumber();
$transferMessage->generate(TransferMessage::CIRCUMFIX_ASTERISK);
echo transferMessage->getStructuredMessage();
```

### Validate a structured message

A valid message

``` php
$transferMessage = new BeBankTransferMessage\TransferMessage();
$transferMessage->setStructuredMessage('+++090/9337/55493+++');
$result = $transferMessage->validate();  // TRUE
```

An invalid message

``` php
$transferMessage = new BeBankTransferMessage\TransferMessage();
$transferMessage->setStructuredMessage('+++011/9337/55493+++');
$result = $transferMessage->validate();  // FALSE
```

Additionally the setter method will throw an ```TransferMessageException()``` if the format of the structured message is not valid.

## Testing

``` bash
$ phpunit
```

## Contributing

Please see [CONTRIBUTING](https://github.com/kouinkouin/be-bank-transfer-message/blob/master/CONTRIBUTING.md) for details.

## Credits

- [Matthias Vandermaesen](https://github.com/Netsensei)
- [kouinkouin](https://github.com/kouinkouin)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
