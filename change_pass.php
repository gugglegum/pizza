<?php

echo "Pizza user's password changer\n\n";

if ($argc != 2) {
    die("Usage: php change_pass.php <email>\n");
}
$email = $argv[1];

echo "Changing password for user \"{$email}\"\n";

require_once(__DIR__ . "/init.php");

/** @var \App\Bootstrap $bootstrap */

/** @var \App\TableManager $tm */
$tm = $bootstrap->getResource('TableManager');

/** @var \App\Models\UsersTable $usersTable */
$usersTable = $tm->getTable("Users");
$select = $usersTable->select();
$select->where('email = ?', $email);
$user = $usersTable->fetchRow($select);
if (! $user) {
    die("User \"{$email}\" not found\n");
}

$newPassword = promptPassword();
echo "New password is set successfully\n";
$user->setPassword($newPassword);
$user->save();

function promptPassword($prompt = "Enter new password: ")
{
    if (preg_match('/^win/i', PHP_OS)) {
        $vbscript = sys_get_temp_dir() . 'prompt_password.vbs';
        file_put_contents(
            $vbscript, 'wscript.echo(InputBox("'
            . addslashes($prompt)
            . '", "", "password here"))');
        $command = "cscript //nologo " . escapeshellarg($vbscript);
        $password = rtrim(shell_exec($command));
        unlink($vbscript);
        return $password;
    } else {
        $command = "/usr/bin/env bash -c 'echo OK'";
        if (rtrim(shell_exec($command)) !== 'OK') {
            trigger_error("Can't invoke bash");
            return;
        }
        $command = "/usr/bin/env bash -c 'read -s -p \""
            . addslashes($prompt)
            . "\" mypassword && echo \$mypassword'";
        $password = rtrim(shell_exec($command));
        echo "\n";
        return $password;
    }
}
