<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App\Controllers;

class AuthController extends AbstractController
{
    /**
     * @var \App\TemplateEngine
     */
    private $_tpl;

    /**
     * @var \App\TableManager
     */
    private $_tm;

    /**
     * @var \App\HelperBroker
     */
    private $_helper;

    /**
     * @var \App\Http\Response
     */
    private $_response;

    public function __construct(\App\Http\Request $request, \App\Bootstrap $bootstrap)
    {
        parent::__construct($request, $bootstrap);
        $this->_tpl = $this->getResource("TemplateEngine");
        $this->_tm = $this->getResource("TableManager");
        $this->_helper = $this->getResource("HelperBroker");
    }

    public function init()
    {
        $this->_response = new \App\Http\Response();
    }

    public function loginAction()
    {
        $form = new \App\Forms\LoginForm();

        if ($this->getRequest()->isPost()) {
            $values = $this->getRequest()->getPostParams();
            $form->setFormValues($values);
            if ($form->isValid()) {
                do {
                    $identity = $form->getValue("email");
                    $identityType = null;
                    $emailValidator = new \App\Validators\Email();
                    if ($emailValidator->isValid($identity)) {
                        $identityType = "email";
                    } else {
                        $form->addFormValidationError(\App\Forms\LoginForm::INVALID_IDENTITY);
                        $this->_response->setStatus(400, "Bad Request");
                        break;
                    }

                    /** @var $usersTable \App\Models\UsersTable */
                    $usersTable = $this->_tm->getTable("Users");
                    $select = $usersTable->select();
                    switch ($identityType)
                    {
                        case "email" :
                            $select->where("email = ?", $identity);
                            break;
                        default :
                            $select->where("false");
                    }

                    $user = $usersTable->fetchRow($select);

                    if (! $user instanceof \App\Models\UsersRow) {
                        $form->addFormValidationError(\App\Forms\LoginForm::USER_NOT_FOUND);
                        $this->_response->setStatus(403, "Forbidden");
                        break;
                    }
                    if (! $user->validatePassword($form->getValue("password"))) {
                        $form->addFormValidationError(\App\Forms\LoginForm::INVALID_PASSWORD);
                        $this->_response->setStatus(403, "Forbidden");
                        break;
                    }

                    $_SESSION["user_id"] = $user->id;

                    return $this->_response->setRedirect($this->getRequest()->hasGetParam('next') ? $this->getRequest()->getGetParam('next') : $this->_helper->url("start"));
                } while (false);
            } else { // if form is not valid
                $this->_response->setStatus(400, "Bad Request");
            }
        }

        $content = $this->_tpl->render("login.phtml", array(
            "form" => $form,
        ));
        $body = $this->_tpl->render("layouts/normal.phtml", array(
            "content" => $content,
            "user" => null,
        ));
        return $this->_response->setBody($body);
    }

    public function logoutAction()
    {
        if (! $this->getRequest()->isPost()) {
            throw new \App\Http\MethodNotAllowedException("This URI may be accessed only with POST method");
        }
        unset($_SESSION["user_id"]);
        session_destroy();

        return $this->_response->setRedirect($this->_helper->url("start"));
    }

    public function registerAction()
    {
        $form = new \App\Forms\RegisterForm();
        if ($this->getRequest()->isPost()) {
            $values = $this->getRequest()->getPostParams();
            $form->setFormValues($values);
            if ($form->isValid()) {
                do {
                    /** @var $usersTable \App\Models\UsersTable */
                    $usersTable = $this->_tm->getTable("Users");
                    $user = $usersTable->createRow(array(
                        "email" => $form->getElement("email")->getValue(),
                        "real_name" => $form->getElement("realName")->getValue(),
                        "created_ts" => time(),
                    ));
                    $password = $form->getElement("password")->getValue();
                    $user->setPassword($password);

                    try {
                        $user->save();
                    } catch (\Zend_Db_Statement_Exception $e) {
                        if (preg_match("/".preg_quote("1062 Duplicate entry '", "/").".*" . preg_quote("' for key ", "/")."(?:2|'email')$/", $e->getMessage())) {
                            $form->addFormValidationError("emailExists");
                            $this->_response->setStatus(400, "Bad Request");
                            break;
                        }
                        throw $e;
                    }

//                    if ($user->email) {
//                        /** @var $emailSender \App\EmailSender */
//                        $emailSender = $this->getResource("EmailSender");
//                        $emailSender->send($user->email, "emails/you_are_registered.phtml", array(
//                            "email" => $user->email,
//                            "password" => $password,
//                        ));
//                    }

                    $content = $this->_tpl->render("registered.phtml", array(
                    ));
                    $body = $this->_tpl->render("layouts/normal.phtml", array(
                        "content" => $content,
                    ));
                    return $this->_response->setBody($body);
                } while (false);
            }
        }

        $content = $this->_tpl->render("register.phtml", array(
            "form" => $form,
        ));
        $body = $this->_tpl->render("layouts/normal.phtml", array(
            "content" => $content,
            "user" => null,
        ));
        return $this->_response->setBody($body);
    }
}
