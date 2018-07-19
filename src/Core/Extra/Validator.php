<?php
namespace Akmb\Core\Extra;

trait Validator
{
    protected $missing = 'missing parameter';

    protected $empty = 'empty parameter';

    protected $errors = [];

    /**
     * Method to ease the mock of $errors attribute
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    private function addError($key, $message)
    {
        if (!isset($this->errors[$key])
            || !in_array($message, $this->errors[$key])
        ) {
            $this->errors[$key][] = $message;
        }
    }

    protected function validatePresence($key, $params, $allowBlank = false)
    {

        if (!isset($params[$key])) {
            $this->addError($key, $this->missing);
            return false;
        } elseif (!$allowBlank && empty($params[$key]) && is_string($params[$key])) {
            $this->addError($key, $this->empty);
            return false;
        }

        return true;
    }

    protected function validateKeysPresence(
        array $keys,
        array $params,
        $allowBlank = false
    ) {
        $valid = true;

        foreach ($keys as $key) {
            if (!$this->validatePresence($key, $params, $allowBlank)) {
                $valid = false;
            }
        }

        return $valid;
    }
}
