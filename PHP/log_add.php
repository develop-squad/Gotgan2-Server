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

$logProduct = validateParameter(Log::LOG_PRODUCT, false, PARAMETER_NUMERIC, -1);
$logUser = validateParameter(Log::LOG_USER, false, PARAMETER_NUMERIC, -1);
$logType = validateParameter(Log::LOG_TYPE, false, PARAMETER_NUMERIC, -1);
$logText = validateParameter(Log::LOG_TEXT, false, PARAMETER_STRING);

# new log
$logIndex = LogManager::getInstance()->addLog([Log::LOG_PRODUCT => $logProduct, LOG::LOG_USER => $logUser, LOG::LOG_TYPE => $logType, LOG::LOG_TEXT => $logText]);

# new log success
printOutput(0, null, [LOG::LOG_INDEX => $logIndex]);

?>
