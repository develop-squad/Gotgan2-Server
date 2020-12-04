<?php

require_once "./inc.php";

if (!SystemManager::getInstance()->checkSystemMaster()) {
    printOutput(-3, MESSAGE_SYSTEM_OFF);
    exit();
}

# session auth
$session = validateParameter(UserSession::SESSION, true, PARAMETER_STRING);
$validation = ServiceManager::getInstance()->validateSession($session);
if ($validation[User::USER_LEVEL] < 1) {
    printOutput(-3, MESSAGE_NOT_ALLOWED);
    exit();
}

$logIndex = validateParameter(Log::LOG_INDEX, true, PARAMETER_NUMERIC);

LogManager::getInstance()->deleteLog($logIndex);

# log deletion success
printOutput(0, null);

?>
