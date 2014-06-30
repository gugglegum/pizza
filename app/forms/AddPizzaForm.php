<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App\Forms;

class AddPizzaForm extends AbstractForm
{
    public function __construct()
    {
        // Pieces

        $element = new Element("pieces");
        $element
            ->addValidator(new \App\Validators\RegexpMatch('/^\\d{1,2}$/'), true)
            ->addValidator(new \App\Validators\Callback(function($value) {
                return $value > 0 && $value < 100;
            }), true)
            ->setRequired(true);
        $this->addElement($element);
    }

}
