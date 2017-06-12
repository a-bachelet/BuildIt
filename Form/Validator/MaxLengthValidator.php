<?php

namespace BuildIt\Form\Validator;

/**
 * Class MaxLength
 * @package BuildIt\Form\Validator
 */
class MaxLengthValidator implements ValidatorInterface
{
    /**
     * @var string $message
     * Error message to display when passed $value is not valid.
     */
    private $message = '';

    /**
     * @var integer $length
     * Maximum length you want for $value.
     */
    private $length = 0;

    /**
     * MaxLengthValidator constructor.
     * @param integer $length
     * @param string $message
     */
    public function __construct($length, $message = '')
    {
        $this->length = $length;
        if ($message === '') {
            $this->message = 'This value should have a maximum length of ' . $this->length . '.';
        } else {
            $this->message = $message;
        }
    }

    /**
     * @inheritdoc
     * @param $value
     * @return bool
     */
    public function validates($value): bool
    {
        if (is_array($value)) {
            return count($value) <= $this->length;
        } elseif (is_string($value)) {
            return strlen($value) <= $this->length;
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function getError(): array
    {
        return ['maxLength', $this->message];
    }
}
