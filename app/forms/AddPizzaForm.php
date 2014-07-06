<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App\Forms;

use App\Validators\Callback;
use App\Validators\RegexpMatch;

class AddPizzaForm extends AbstractForm
{
    public function __construct()
    {
        // Pieces

        $element = new Element("pieces");
        $element
            ->addValidator(new RegexpMatch('/^\d+$/u'), true)
            ->addValidator(new Callback(function($value) {
                return (int) $value > 0 && (int) $value < 64;
            }), true)
            ->setRequired(true);
        $this->addElement($element);
    }

}
