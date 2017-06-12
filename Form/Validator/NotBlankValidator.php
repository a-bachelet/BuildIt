<?php

namespace BuildIt\Form\Validator;

/**
 * Class NotBlank
 * @package BuildIt\Form\Validator
 */
class NotBlankValidator implements ValidatorInterface
{
    /**
     * @var string $message
     * Error message to display when passed $value is not valid.
     */
    private $message = '';

    /**
     * NotBlankValidator constructor.
     * @param string $message
     */
    public function __construct($message = '')
    {
        if ($message === '') {
            $this->message = 'This value should not be blank.';
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
        if(is_array($value)) {
            return count($value) !== 0;
        } else {
            return (isset($value) && $value !== '' && $value !== false);
        }
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function getError(): array
    {
        return ['notBlank', $this->message];
    }
}
