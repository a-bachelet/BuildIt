<?php

namespace BuildIt\Form\Validator;

class EmailValidator implements ValidatorInterface
{
    /**
     * @var string $message
     * Error message to display when passed $value is not valid.
     */
    private $message = '';

    /**
     * EmailValidator constructor.
     * @param string $message
     */
    public function __construct($message = '')
    {
        if ($message === '') {
            $this->message = 'This value is not a valid email.';
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
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function getError(): array
    {
        return ['email', $this->message];
    }
}
