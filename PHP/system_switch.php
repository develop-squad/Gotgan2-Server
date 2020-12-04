<?php

require_once "./inc.php";

$session = validateParameter(UserSession::SESSION, true, PARAMETER_STRING);
$validation = ServiceManager::getInstance()->validateSession($session);
if ($validation[User::USER_LEVEL] < 2) {
    printOutput(-3, MESSAGE_NOT_ALLOWED);
    exit();
}

$switchMaster = validateParameter(KEY_SWITCH_MASTER, false, PARAMETER_NUMERIC);
if (isset($switchMaster)) {
    SystemManager::getInstance()->switchSystemMaster($switchMaster);
}
$switchLogin = validateParameter(KEY_SWITCH_LOGIN, false, PARAMETER_NUMERIC);
if (isset($switchLogin)) {
    SystemManager::getInstance()->switchSystemLogin($switchLogin);
}
$switchRent = validateParameter(KEY_SWITCH_RENT, false, PARAMETER_NUMERIC);
if (isset($switchRent)) {
    SystemManager::getInstance()->switchSystemRent($switchRent);
}
$switchMessage = validateParameter(KEY_SWITCH_MESSAGE, false, PARAMETER_NUMERIC);
if (isset($switchMessage)) {
    SystemManager::getInstance()->switchSystemMessage($switchMessage);
}

# system switch success
printOutput(0, "", [
    KEY_SWITCH_MASTER => SystemManager::getInstance()->checkSystemMaster(),
    KEY_SWITCH_LOGIN => SystemManager::getInstance()->checkSystemLogin(),
    KEY_SWITCH_RENT => SystemManager::getInstance()->checkSystemRent(),
    KEY_SWITCH_MESSAGE => SystemManager::getInstance()->checkSystemMessage()]);

?>
