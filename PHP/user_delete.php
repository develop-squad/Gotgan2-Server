<?php

require_once "./inc.php";

if (!SystemManager::getInstance()->checkSystemMaster()) {
    printOutput(-3, MESSAGE_SYSTEM_OFF);
    exit();
}

$userIndex = validateParameter(User::USER_INDEX, true, PARAMETER_NUMERIC);

$session = validateParameter(UserSession::SESSION, true, PARAMETER_STRING);
$validation = ServiceManager::getInstance()->validateSession($session);
if ($validation[User::USER_LEVEL] < 1) {
    if ($validation[User::USER_INDEX] != $userIndex) {
        printOutput(-3, MESSAGE_NOT_ALLOWED);
        exit();
    }
}

UserManager::getInstance()->deleteUser([User::USER_INDEX => $userIndex], $validation);

# user deletion success
printOutput(0, null);

?>
