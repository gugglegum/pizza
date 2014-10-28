<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App\Forms;

use App\Filters\Trim;
use App\Validators\Callback;
use App\Validators\RegexpMatch;

class CreateOrderForm extends AbstractForm
{
    public function __construct()
    {
        // delivery
        $element = new Element("delivery");
        $element
            ->addPreFilter(new Trim())
            ->addValidator(new Callback(function($date) {
                if (preg_match('/^(\\d{4})-(\\d{2})-(\\d{2}) (\\d{2}):(\\d{2}):(\\d{2})$/', $date, $m)) {
                    return checkdate($m[2], $m[3], $m[1])
                        && $m[4] >= 0 && $m[4] <= 23
                        && $m[5] >= 0 && $m[5] <= 59
                        && $m[6] >= 0 && $m[6] <= 59;
                } else {
                    return false;
                }
            }), true)
            ->setRequired(true);

        $this->addElement($element);

        // discount_absolute
        $element = new Element("discount_absolute");
        $element
            ->addPreFilter(new Trim())
            ->addValidator(new RegexpMatch('/^\\d*$/'), true);

        $this->addElement($element);

        // discount_percent
        $element = new Element("discount_percent");
        $element
            ->addPreFilter(new Trim())
        ->addValidator(new RegexpMatch('/^\\d+(\\.\\d+)?|$/'), true);

        $this->addElement($element);

        // note
        $element = new Element("note");
        $element
            ->addPreFilter(new Trim());

        $this->addElement($element);

    }

}
