<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App\Forms;

class Element
{
    /**
     * Код ошибки, который сохраняется в $this->_validateErrors, если
     * при валидации выяснилось, что поле пустое, а оно не должно таким быть
     */
    const ERROR_REQUIRED = "required";

    private $_name;
    private $_value;
    private $_isRequired = false;
    private $_emptyPattern = "/^$/";
    private $_preFilters = array();
    private $_postFilters = array();
    private $_validators = array();
    private $_validateErrors = array();

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->_name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->_value;
    }

    /**
     * @param $value
     * @return Element
     */
    public function setValue($value)
    {
        $this->_value = $value;
        $this->preFilterValue();
        return $this;
    }

    /**
     * Аналогичен setValue(), но не вызывает вызов пре-фильтров.
     * Этот метод необходим для корректной работы фильтров,
     * которые в противном случае приводили бы к бесконечной
     * рекурсии.
     *
     * @param $value
     * @return Element
     */
    protected function _setValue($value)
    {
        $this->_value = $value;
        return $this;
    }

    /**
     * Устанавливает флаг обязательного поля
     *
     * Если TRUE, то поле не должно быть пустым
     *
     * @param $isRequired
     * @return Element
     */
    public function setRequired($isRequired)
    {
        $this->_isRequired = (bool) $isRequired;
        return $this;
    }

    /**
     * Возвращает текущее значение флага $isRequired
     *
     * @return bool
     */
    public function isRequired()
    {
        return $this->_isRequired;
    }

    /**
     * Возвращает TRUE, если данное поле формы содержит значение,
     * которое распознается как пустое
     *
     * @return bool
     */
    public function isEmpty()
    {
        return (bool) preg_match($this->_emptyPattern, $this->getValue());
    }

    /**
     * Устанавливает регулярное выражение, которое соответствует
     * пустому значению поля формы. Если значение соответствует
     * данному шаблону, то оно признается пустым, а следовательно
     * отрабатывает проверка на непустое значение (setRequired) и
     * для него не выполняются валидаторы (смысл валидировать пустое
     * значение?)
     *
     * @param string $emptyPattern
     */
    public function setEmptyPattern($emptyPattern)
    {
        $this->_emptyPattern = $emptyPattern;
    }

    /**
     * Добавляет фильтр, который будет вызываться ПЕРЕД валидацией поля
     *
     * @param \App\Filters\AbstractFilter $filter
     * @return Element
     */
    public function addPreFilter(\App\Filters\AbstractFilter $filter)
    {
        $this->_preFilters[] = $filter;
        return $this;
    }

    /**
     * Добавляет фильтр, который будет вызываться ПОСЛЕ валидацией поля
     *
     * @param \App\Filters\AbstractFilter $filter
     * @return Element
     */
    public function addPostFilter(\App\Filters\AbstractFilter $filter)
    {
        $this->_postFilters[] = $filter;
        return $this;
    }

    public function preFilterValue()
    {
        /** @var $filter \App\Filters\AbstractFilter */
        foreach ($this->_preFilters as $filter) {
            $this->_setValue($filter->filter($this->getValue()));
        }
        return $this;
    }

    public function postFilterValue()
    {
        /** @var $filter \App\Filters\AbstractFilter */
        foreach ($this->_postFilters as $filter) {
            $this->_setValue($filter->filter($this->getValue()));
        }
        return $this;
    }

    /**
     * @param \App\Validators\AbstractValidator $validator
     * @param bool $break Не проверять остальные валидаторы, если споткнулись на этом
     * @param string $name Имя валидатора
     * @param array $data Опциональные данные, которые не передаются в валидатор, но возвращаются вместе с ошибкой и могут быть использованы при выводе информативного сообщения об ошибке
     * @return Element
     */
    public function addValidator(\App\Validators\AbstractValidator $validator, $break, $name = null, $data = array())
    {
        $this->_validators[] = array(
            "validator" => $validator,
            "break" => (bool) $break,
            "name" => $name,
            "data" => $data,
        );
        return $this;
    }

    /**
     * @return bool
     * @throws \App\Exception
     */
    public function isValid()
    {
        $this->_validateErrors = array();
        $value = $this->getValue();
        $valid = true;

        if ($this->isEmpty()) {
            if ($this->isRequired()) {
                $valid = false;
                $this->_validateErrors[] = array(
                    "code" => self::ERROR_REQUIRED,
                    "name" => null,
                    "data" => [],
                    "validator" => null,
                );
            }
        } else {
            /** @var $validator \App\Validators\AbstractValidator */
            foreach ($this->_validators as $validatorData) {
                $validator = $validatorData["validator"];
                $break = $validatorData["break"];
                if (! $validator->isValid($value)) {
                    $valid = false;
                    if (! $lastErrorCode = $validator->getLastErrorCode()) {
                        throw new \App\Exception("Validator " . get_class($validator) . " didn't set _lastErrorCode on validation failure");
                    }
                    $this->_validateErrors[] = array(
                        "code" => $lastErrorCode,
                        "name" => $validatorData["name"],
                        "data" => $validatorData["data"],
                        "validator" => $validator,
                    );
                    if ($break) {
                        break;
                    }
                }
            }

            $this->postFilterValue();
        }
        return $valid;
    }

    public function getValidateErrors()
    {
        return $this->_validateErrors;
    }

    public function addValidateError($errorCode)
    {
        $this->_validateErrors[] = $errorCode;
    }

}
