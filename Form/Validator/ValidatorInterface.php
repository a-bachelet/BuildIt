<?php

namespace BuildIt\Form\Validator;

/**
 * Interface ValidatorInterface
 * @package BuildIt\Form\Validator
 */
interface ValidatorInterface {
    /**
     * Tells if $value is valid or not.
     * @param $value
     * @return bool
     */
    public function validates($value): bool;

    /**
     * Returns an array with error name => error message.
     * @return array
     */
    public function getError(): array;
}
