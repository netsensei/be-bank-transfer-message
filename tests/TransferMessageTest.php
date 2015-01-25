<?php

namespace Netsensei\BeBankTransferMessage\Test;

use Netsensei\BeBankTransferMessage\TransferMessage;
use Netsensei\BeBankTransferMessage\Exception\TransferMessageException;

class TransferMessageTest extends \PHPUnit_Framework_TestCase
{

    public function testNumberSetterNotAnInt()
    {
        $transferMessage = new TransferMessage();
        try {
            $transferMessage->setNumber('abcd');
        } catch (TransferMessageException $e) {
            $previous = $e->getPrevious();
            $this->assertInstanceOf('\InvalidArgumentException', $previous);
            $this->assertEquals(
                'The number should be an integer larger then 0 and smaller then 9999999999.',
                $previous->getMessage()
            );

            return;
        }

        $this->markTestIncomplete('This test should have thrown an exception');
    }

    public function testNumberSetterOutOfLowerBound()
    {
        $transferMessage = new TransferMessage();
        try {
            $number = 0;
            $transferMessage->setNumber($number);
        } catch (TransferMessageException $e) {
            $previous = $e->getPrevious();
            $this->assertInstanceOf('\InvalidArgumentException', $previous);
            $this->assertEquals(
                'The number should be an integer larger then 0 and smaller then 9999999999.',
                $previous->getMessage()
            );

            return;
        }

        $this->markTestIncomplete('This test should have thrown an exception');
    }

    public function testNumberSetterOutofUpperBound()
    {
        $transferMessage = new TransferMessage();
        $number = 10000000000;
        try {
            $number = 0;
            $transferMessage->setNumber($number);
        } catch (TransferMessageException $e) {
            $previous = $e->getPrevious();
            $this->assertInstanceOf('\InvalidArgumentException', $previous);
            $this->assertEquals(
                'The number should be an integer larger then 0 and smaller then 9999999999.',
                $previous->getMessage()
            );

            return;
        }

        $this->markTestIncomplete('This test should have thrown an exception');
    }

    public function testModulusGetter()
    {
        $transferMessage = new TransferMessage(119698);
        $modulus = $transferMessage->getModulus();

        $this->assertEquals(TransferMessage::MODULO, $modulus);

        $transferMessage->setNumber(123456);
        $transferMessage->generate();
        $modulus = $transferMessage->getModulus();

        $this->assertEquals(72, $modulus);
    }

    public function testNumberGetter()
    {
        $expectedNumber = 119698;
        $transferMessage = new TransferMessage($expectedNumber);
        $actual = $transferMessage->getNumber();

        $this->assertEquals($expectedNumber, $actual);
    }

    public function testGeneratedMessageFormat()
    {
        $pattern = '/^[\+\*]{3}[0-9]{3}[\/]?[0-9]{4}[\/]?[0-9]{5}[\+\*]{3}$/';

        $transferMessage = new TransferMessage();

        $structuredMessage = $transferMessage->getStructuredMessage();
        $this->assertRegexp($pattern, $structuredMessage);

        $transferMessage->generate(TransferMessage::CIRCUMFIX_ASTERISK);
        $structuredMessage = $transferMessage->getStructuredMessage();
        $this->assertRegexp($pattern, $structuredMessage);

        $transferMessage->setNumber(1);
        $transferMessage->generate();
        $structuredMessage = $transferMessage->getStructuredMessage();
        $this->assertRegexp($pattern, $structuredMessage);
    }

    public function testStructuredMessageSetterInvalidInput()
    {
        $transferMessage = new TransferMessage();

        try {
            $transferMessage->setStructuredMessage('+++000\0119\69897+++');
        } catch (TransferMessageException $e) {
            $previous = $e->getPrevious();
            $this->assertInstanceOf('\InvalidArgumentException', $previous);
            $this->assertEquals(
                'The structured message does not have a valid format.',
                $previous->getMessage()
            );

            return;
        }

        $this->markTestIncomplete('This test should have thrown an exception');
    }

    public function testValidateStructuredMessage()
    {
        // Number with carry > 0
        $transferMessage = new TransferMessage(123456);
        $result = $transferMessage->validate();
        $this->assertTrue($result);

        // Number with carry = 0
        $transferMessage->setNumber(119698);
        $transferMessage->generate();
        $result = $transferMessage->validate();
        $this->assertTrue($result);

        // With 0's prepadded
        $transferMessage->setNumber(1);
        $result = $transferMessage->validate();
        $this->assertTrue($result);

        // Carry = 0
        $transferMessage->setStructuredMessage('+++000/0119/69897+++');
        $result = $transferMessage->validate();
        $this->assertTrue($result);

        // Carry > 0
        $transferMessage->setStructuredMessage('+++090/9337/55493+++');
        $result = $transferMessage->validate();
        $this->assertTrue($result);

        // With asterisks
        $transferMessage->setStructuredMessage('***090/9337/55493***');
        $result = $transferMessage->validate();
        $this->assertTrue($result);

        // Invalid structured message
        $transferMessage->setStructuredMessage('+++011/9337/55493+++');
        $result = $transferMessage->validate();
        $this->assertFalse($result);
    }
}
