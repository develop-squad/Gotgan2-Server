<?php

require_once "./inc.php";

if (!SystemManager::getInstance()->checkSystemMaster()) {
    printOutput(-3, MESSAGE_SYSTEM_OFF);
    exit();
}

# session auth
$session = validateParameter(UserSession::SESSION, true, PARAMETER_STRING);
$validation = ServiceManager::getInstance()->validateSession($session);

# prepare log type list result
$logTypeJsonResult = LogManager::getInstance()->getLogTypes();

# log type list success
printOutput(0, null);

?>
