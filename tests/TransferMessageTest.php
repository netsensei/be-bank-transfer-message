<?php

namespace Netsensei\BeBankTransferMessage\Test;

use Netsensei\BeBankTransferMessage\TransferMessage;
use Netsensei\BeBankTransferMessage\Exception\TransferMessageException;

class TransferMessageTest extends \PHPUnit_Framework_TestCase
{

    public function testConstructorNumberUndefined() {
        $transferMessage = new TransferMessage();
        $number = $transferMessage->getNumber();
        $this->assertNotNull($number);
    }

    public function testConstructorNumberDefined() {
        $transferMessage = new TransferMessage(12345);
        $number = $transferMessage->getNumber();
        $this->assertNotNull($number);
    }

    public function testNumberSetterNotAnInt() {
        $transferMessage = new TransferMessage();
        try {
            $number = 'abcd';
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

    public function testNumberSetterOutOfLowerBound() {
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

    public function testNumberSetterOutofUpperBound() {
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

    public function testCarryGetterNotNull() {
        $transferMessage = new TransferMessage();
        $transferMessage->generate();
        $carry = $transferMessage->getCarry();

        $this->assertNotNull($carry);
    }

    public function testCarryGetterIsModZeroException() {
        $transferMessage = new TransferMessage(119698);
        $transferMessage->generate();
        $carry = $transferMessage->getCarry();

        $this->assertEquals($transferMessage::MOD_BE_BANK_TRANSFER_MESSAGE, $carry);
    }
}
