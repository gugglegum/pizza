<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App\Models;

/**
 * Пользователь сайта
 *
 * @property $id int                ID пользователя
 * @property $email string          E-mail
 * @property $password_hash string  Хэш пароля
 * @property $password_salt string  Соль для хэша пароля
 * @property $real_name string      Настоящее имя
 * @property $created_ts int        Дата регистрации в unix
 */
class UsersRow extends AbstractRow
{
    public function validatePassword($password)
    {
        return $this->_getPasswordHash($password) == $this->password_hash;
    }

    public function setPassword($password)
    {
        $this->password_salt = $this->generatePassword(8);
        $this->password_hash = $this->_getPasswordHash($password);
    }

    public function generatePassword($length)
    {
        $chars = "abcdefghijkmnpqrstuvwxyz23456789";
        $password = "";
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars{mt_rand(0, strlen($chars)-1)};
        }
        return $password;
    }

    private function _getPasswordHash($password)
    {
        return md5($this->password_salt . $password);
    }
}
