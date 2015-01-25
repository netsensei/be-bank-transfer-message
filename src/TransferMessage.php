<?php

namespace Netsensei\BeBankTransferMessage;

use Netsensei\BeBankTransferMessage\Exception\TransferMessageException;

class TransferMessage
{

    const MODULO = 97;

    const CIRCUMFIX_ASTERISK = "*";

    const CIRCUMFIX_PLUS = "+";

    private $number;

    private $modulus;

    private $structuredMessage = NULL;

    public function __construct($number = NULL) {
        $this->setNumber($number);
        $this->generate();
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

    public function getModulus() {
        return $this->modulus;
    }

    public function setStructuredMessage($structuredMessage) {
        try {
            $pattern = '/^[\+\*]{3}[0-9]{3}[\/]?[0-9]{4}[\/]?[0-9]{5}[\+\*]{3}$/';
            if (preg_match($pattern, $structuredMessage) === 0) {
                throw new \InvalidArgumentException('The structured message does not have a valid format.');
            } else {
                $this->structuredMessage = $structuredMessage;
            }
        } catch (\Exception $e) {
            throw new TransferMessageException('Failed to validate the format of the structured message', null, $e);
        }
    }

    public function getStructuredMessage() {
        return $this->structuredMessage;
    }

    public function generate($circumfix = self::CIRCUMFIX_PLUS) {
        $this->modulus = $this->mod($this->number);

        $structuredMessage = str_pad($this->number, 10, 0, STR_PAD_LEFT) . str_pad($this->modulus, 2, 0, STR_PAD_LEFT);

        $pattern = array('/^([0-9]{3})([0-9]{4})([0-9]{5})$/');
        $replace = array(str_pad('$1/$2/$3', 14, $circumfix, STR_PAD_BOTH));
        $this->structuredMessage = preg_replace($pattern, $replace, $structuredMessage);
    }

    public function validate() {
        $pattern = array('/^[\+\*]{3}([0-9]{3})[\/]?([0-9]{4})[\/]?([0-9]{5})[\+\*]{3}$/');
        $replace = array('${1}${2}${3}');
        $rawStructuredMessage = preg_replace($pattern, $replace, $this->structuredMessage);

        $number = substr($rawStructuredMessage, 0, 10);
        $modulus = substr($rawStructuredMessage, 10, 2);

        return ($modulus == $this->mod($number)) ? TRUE : FALSE;
    }

    private function mod($dividend) {
        $modulus = $dividend % self::MODULO;
        return ($modulus > 0) ? $modulus : self::MODULO;
    }
}
