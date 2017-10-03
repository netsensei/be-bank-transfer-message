<?php

namespace Netsensei\BeBankTransferMessage;

use Netsensei\BeBankTransferMessage\Exception\TransferMessageException;

class TransferMessage
{
    /**
     * Set divisor used to calculate the modulus
     */
    const MODULO = 97;

    /**
     * Set the asterisk sign as a circumfix
     */
    const CIRCUMFIX_ASTERISK = "*";

    /**
     * Set the plus sign as a circumfix
     */
    const CIRCUMFIX_PLUS = "+";

    /**
     * The number used to generate a structured message
     *
     * @var int
     */
    private $number;

    /**
     * The modulus resulting from the modulo operation
     *
     * @var int
     */
    private $modulus;

    /**
     * A structured message with a valid formatting
     *
     * @var string
     */
    private $structuredMessage = null;

    /**
     * Create a new instance
     *
     * @param int $number The number used to generate a structured message
     */
    public function __construct($number = null)
    {
        $this->setNumber($number);
        $this->generate();
    }

    /**
     * Generate a valid structured message based on the number
     *
     * @param  string $circumfix The circumfix. Defaults to the plus sign
     *
     * @return string            A valid structured message
     */
    public function generate($circumfix = self::CIRCUMFIX_PLUS)
    {
        $this->modulus = $this->mod($this->number);

        $structuredMessage = str_pad($this->number, 10, 0, STR_PAD_LEFT).str_pad($this->modulus, 2, 0, STR_PAD_LEFT);

        $pattern = ['/^([0-9]{3})([0-9]{4})([0-9]{5})$/'];
        $replace = [str_pad('$1/$2/$3', 14, $circumfix, STR_PAD_BOTH)];
        $this->structuredMessage = preg_replace($pattern, $replace, $structuredMessage);

        return $this->structuredMessage;
    }

    /**
     * The mod97 calculation
     *
     * If the modulus is 0, the result is substituted to 97
     *
     * @param  int $dividend The dividend
     *
     * @return int           The modulus
     */
    private function mod($dividend)
    {
        $modulus = $dividend % self::MODULO;

        return ($modulus > 0) ? $modulus : self::MODULO;
    }

    /**
     * Get the number
     *
     * @return int The number used to generate a structured message
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set the number
     *
     * If no number is passed to this method, a random number will be generated
     *
     * @param int $number The number used to generate a structured message
     *
     * @throws TransferMessageException If the number is out of bounds
     */
    public function setNumber($number = null)
    {
        try {
            if (is_null($number)) {
                if (version_compare(PHP_VERSION, '7.0.0', '>=')) {
                    $this->number = random_int(1, 9999999999);
                } else {
                    $this->number = mt_rand(1, 9999999999);
                }
            } else {
                if (($number < 1) || ($number > 9999999999)) {
                    throw new \InvalidArgumentException(
                        'The number should be an integer larger then 0 and smaller then 9999999999.'
                    );
                }

                $this->number = $number;
            }
        } catch (\Exception $e) {
            throw new TransferMessageException('Failed to set number', null, $e);
        }
    }

    /**
     * Get the modulus
     *
     * @return int The modulus resulting from the modulo operation
     */
    public function getModulus()
    {
        return $this->modulus;
    }

    /**
     * Get the structured message
     *
     * @return string A valid formatted structured message
     */
    public function getStructuredMessage()
    {
        return $this->structuredMessage;
    }

    /**
     * Set a structured message
     *
     * @param string $structuredMessage A structured message
     *
     * @throws  TransferMessageException If the format is not valid
     */
    public function setStructuredMessage($structuredMessage)
    {
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

    /**
     * Validates a structured message
     *
     * The validation is the mod97 calculation of the number and comparison of
     * the result to the provided modulus.
     *
     * @return bool TRUE if valid, FALSE if invalid
     */
    public function validate()
    {
        $pattern = ['/^[\+\*]{3}([0-9]{3})[\/]?([0-9]{4})[\/]?([0-9]{5})[\+\*]{3}$/'];
        $replace = ['${1}${2}${3}'];
        $rawStructuredMessage = preg_replace($pattern, $replace, $this->structuredMessage);

        $number = substr($rawStructuredMessage, 0, 10);
        $modulus = substr($rawStructuredMessage, 10, 2);

        return ($modulus == $this->mod($number)) ? true : false;
    }
}
