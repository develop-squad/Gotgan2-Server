<?php

require_once "./inc.php";

if (!SystemManager::getInstance()->checkSystemMaster()) {
    printOutput(-3, MESSAGE_SYSTEM_OFF);
    exit();
}

$session = validateParameter(UserSession::SESSION, true, PARAMETER_STRING);
$validation = ServiceManager::getInstance()->validateSession($session);
if ($validation[User::USER_LEVEL] < 1) {
    printOutput(-3, MESSAGE_NOT_ALLOWED);
    exit();
}

$userIndex = validateParameter(User::USER_INDEX, false, PARAMETER_NUMERIC);
$mailTitle = validateParameter(KEY_TITLE, true, PARAMETER_STRING);
$mailMessage = validateParameter(KEY_MESSAGE, true, PARAMETER_STRING);

SystemManager::getInstance()->sendEmailByApi($mailTitle, $mailMessage, [User::USER_INDEX => $userIndex], $validation);

# send mail success
printOutput(0, null);

?>
