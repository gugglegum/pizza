<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App\Forms;

use App\Filters\Trim;

class LoginForm extends AbstractForm
{
    const USER_NOT_FOUND = "userNotFound";
    const INVALID_IDENTITY = "invalidIdentity";
    const INVALID_PASSWORD = "invalidPassword";

    public function __construct()
    {
        // Identity
        $element = new Element("email");
        $element->addPreFilter(new Trim())
            ->setRequired(true);
        $this->addElement($element);

        // Password
        $element = new Element("password");
        $element->setRequired(true);
        $this->addElement($element);
    }

}
