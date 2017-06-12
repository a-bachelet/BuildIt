<?php

namespace BuildIt\Form;

use BuildIt\Form\Validator\ValidatorInterface;

/**
 * Class Form
 * @package BuildIt\Form
 */
abstract class Form
{
    /**
     * @var array $fields
     * Fields of the Form.
     */
    private $fields = [];

    /**
     * @var array $errors
     * Errors of the Form.
     */
    private $errors = [];

    /**
     * @var bool $valid
     * Valid state of the Form.
     */
    private $valid = false;

    /**
     * Adds a field in the Form $fields.
     * @param string $name
     * @param ValidatorInterface[] $validators
     */
    protected function add($name, $validators)
    {
        $field = new Field($name, $validators);
        $this->fields[$name] = $field;
    }

    /**
     * Handles the $values array and process on it.
     * @param array $values
     */
    public function handleValues($values)
    {
        foreach ($values as $k => $v) {
            if (isset($this->fields[$k])) {
                $this->fields[$k]->setValue($v);
            }
        }
    }

    /**
     * Tells if the Form is valid or not.
     * @return bool
     */
    public function isValid() {
        $this->valid = true;
        foreach ($this->fields as $field) {
            if (!$field->isValid()[0] === true) {
                $this->valid = false;
                $this->errors[$field->getName()] = $field->isValid()[1];
            }
        }
        return $this->valid;
    }

    /**
     * Returns Form errors.
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
