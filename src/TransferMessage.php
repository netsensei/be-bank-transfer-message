<?php

namespace Colada\BeBankTransferMessage;

use Colada\BeBankTransferMessage\Exception\TransferMessageException;

class TransferMessage
{

    const MOD_BE_BANK_TRANSFER_MESSAGE = 97;

    private $number;

    private $carry = NULL;

    private $structuredMessage = NULL;

    public function __construct($number = NULL) {
        $this->setNumber($number);
    }

    public function setNumber($number = NULL) {
        try {
            if (is_null($number)) {
                $this->number = mt_rand(1, 9999999999);
            } else {
                if (($number < 1) || ($number > 9999999999)) {
                    throw new \InvalidArgumentException('The number should be larger then 0 and smaller then 9999999999.');
                }
                $this->number = $number;
           }
        } catch (\Exception $e) {
            throw new TransferMessageException('Failed to set number', null, $e);
        }
    }

    public function getNumber() {
        return $this->number;
    }

    public function getCarry() {
        return $this->carry;
    }

    public function setStructuredMessage($structuredMessage) {
        $this->structuredMessage = $structuredMessage;
    }

    public function getStructuredMessage() {
        return $this->structuredMessage;
    }

    public function generate() {
        $carry = $this->number % self::MOD_BE_BANK_TRANSFER_MESSAGE;
        $this->carry = ($carry > 0) ? $carry : self::MOD_BE_BANK_TRANSFER_MESSAGE;

        $structuredMessage = str_pad($number, 10, STR_PAD_LEFT) . str_pad($carry, 2, STR_PAD_LEFT);

        $pattern = array('/^([0-9]{3})([0-9]{4})([0-9]{5})$/');
        $replace = array('+++$1/$2/$3+++');
        $this->structuredMessage = preg_replace($pattern, $replace, $structuredMessage);
    }
}

