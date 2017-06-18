<?php

namespace BuildIt\Form;

use BuildIt\Form\Validator\ValidatorInterface;

/**
 * Class Field
 * @package BuildIt\Form
 */
class Field
{
    /**
     * @var string $name;
     * Name of the Field.
     */
    private $name = null;

    /**
     * @var ValidatorInterface[] $validators
     * Array of validators which will validate this Field.
     */
    private $validators = [];

    /**
     * @var $value
     * Value passed to this Field.
     */
    private $value = null;

    /**
     * @var array $error
     * First error which will be returned by one of the validators.
     */
    private $error = null;

    /**
     * Field constructor.
     * @param $name
     * @param $validators
     */
    public function __construct($name, $validators)
    {
        $this->name = $name;
        $this->validators = $validators;
    }

    /**
     * Tells if the Field is valid or not.
     * @return array|bool
     */
    public function isValid()
    {
        if (!isset($this->value)) {
            return false;
        } else {
            $valid = true;
            foreach ($this->validators as $validator) {
                if ($valid === true) {
                    $valid = $validator->validates($this->value);
                    if (!$valid) {
                        $this->error = $validator->getError();
                    }
                }
            }
            return [$valid, $this->error];
        }
    }

    /**
     * Set value.
     * @param $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Get name.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
