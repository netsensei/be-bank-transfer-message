<?php

namespace Netsensei\BeBankTransferMessage\Test;

use Netsensei\BeBankTransferMessage\TransferMessage;
use Netsensei\BeBankTransferMessage\Exception\TransferMessageException;

class TransferMessageTest extends \PHPUnit_Framework_TestCase
{

    private $transferMessage;

    public function setUp() {
        $this->transferMessage = new TransferMessage();
    }

    public function testNumberSetterOutOfLowerBound() {
        try {
            $number = 0;
            $this->transferMessage->setNumber($number);
        } catch (TransferMessageException $e) {
            $previous = $e->getPrevious();
            $this->assertInstanceOf('\InvalidArgumentException', $previous);
            $this->assertEquals(
                'The number should be larger then 0 and smaller then 9999999999.',
                $previous->getMessage()
            );

            return;
        }

        $this->markTestIncomplete('This test should have thrown an exception');
    }

    public function testNumberSetterOutofUpperBound() {

        $number = 10000000000;
        try {
            $number = 0;
            $this->transferMessage->setNumber($number);
        } catch (TransferMessageException $e) {
            $previous = $e->getPrevious();
            $this->assertInstanceOf('\InvalidArgumentException', $previous);
            $this->assertEquals(
                'The number should be larger then 0 and smaller then 9999999999.',
                $previous->getMessage()
            );

            return;
        }

        $this->markTestIncomplete('This test should have thrown an exception');
    }
}
