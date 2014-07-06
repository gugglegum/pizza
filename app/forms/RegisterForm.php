<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App\Forms;

use App\Filters\Trim;
use App\Validators\Email;
use App\Validators\RegexpMatch;

class RegisterForm extends AbstractForm
{
    const ERROR_NO_EMAIL = "noEmail";

    public function __construct()
    {
        // E-mail
        $element = new Element("email");
        $element->setRequired(true)
            ->addPreFilter(new Trim())
            ->addValidator(new Email(), true);
        $this->addElement($element);

        // Password
        $element = new Element("password");
        $element->setRequired(true)
            ->addValidator(new RegexpMatch('/^.{4,}$/u'), true, "length", ['minLength' => 4]);
        $this->addElement($element);

        // Real name
        $element = new Element("realName");
        $element->setRequired(true)
            ->addPreFilter(new Trim())
            ->addValidator(new RegexpMatch('/^.{4,}$/u'), true, "length", ['minLength' => 4]);
        $this->addElement($element);
    }

    public function isValid()
    {
        $valid = true;
        if ($this->getElement("email")->isEmpty()) {
            $this->addFormValidationError(self::ERROR_NO_EMAIL);
            $valid = false;
        }
        return $valid && parent::isValid();
    }

}
