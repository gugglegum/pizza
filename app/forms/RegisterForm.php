<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App\Forms;

class RegisterForm extends AbstractForm
{
    const ERROR_NO_EMAIL = "noEmail";

    public function __construct()
    {
        // E-mail
        $element = new Element("email");
        $element->setRequired(true)
            ->addPreFilter(new \App\Filters\Trim())
            ->addValidator(new \App\Validators\Email(), true);
        $this->addElement($element);

        // Password
        $element = new Element("password");
        $element->setRequired(true)
            ->addValidator(new \App\Validators\RegexpMatch('/^.{4,}$/'), true);
        $this->addElement($element);

        // Real name
        $element = new Element("realName");
        $element->setRequired(true)
            ->addPreFilter(new \App\Filters\Trim())
            ->addValidator(new \App\Validators\RegexpMatch('/^.{4,}$/'), true);
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
