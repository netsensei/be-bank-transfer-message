<?php

namespace Netsensei\BeBankTransferMessage;

use Netsensei\BeBankTransferMessage\Exception\TransferMessageException;

class TransferMessage
{

    const MODULO = 97;

    const CIRCUMFIX_ASTERISK = "*";

    const CIRCUMFIX_PLUS = "+";

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
                    throw new \InvalidArgumentException('The number should be an integer larger then 0 and smaller then 9999999999.');
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

    public function generate($circumfix = self::CIRCUMFIX_PLUS) {
        $carry = $this->number % self::MODULO;
        $this->carry = ($carry > 0) ? $carry : self::MODULO;

        $structuredMessage = str_pad($this->number, 10, 0, STR_PAD_LEFT) . str_pad($carry, 2, 0, STR_PAD_LEFT);

        $pattern = array('/^([0-9]{3})([0-9]{4})([0-9]{5})$/');
        $replace = array(str_pad('$1/$2/$3', 14, $circumfix, STR_PAD_BOTH));
        $this->structuredMessage = preg_replace($pattern, $replace, $structuredMessage);
    }
}

