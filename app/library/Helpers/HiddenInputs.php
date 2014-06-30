<?php

namespace App\Helpers;

/**
 * Возвращает массив скрытых инпутов на основе заданного ассоциативного массива
 */
class HiddenInputs extends AbstractHelper
{
    /**
     * @param array $formData
     * @return array
     */
    public function execute(array $formData)
    {
        $inputs = array();
        foreach ($formData as $key => $value) {
            $inputs[] = '<input type="hidden" name="'.$this->_helperBroker->escape($key).'" value="'.$this->_helperBroker->escape($value).'" />';
        }
        return $inputs;
    }
}
