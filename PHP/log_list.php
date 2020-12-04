<?php

require_once "./inc.php";

if (!SystemManager::getInstance()->checkSystemMaster()) {
    printOutput(-3, MESSAGE_SYSTEM_OFF);
    exit();
}

$session = validateParameter(UserSession::SESSION, true, PARAMETER_STRING);
$validation = ServiceManager::getInstance()->validateSession($session);

$logIndex = validateParameter(Log::LOG_INDEX, false, PARAMETER_NUMERIC);
$logProduct = validateParameter(Log::LOG_PRODUCT, false, PARAMETER_NUMERIC);
$logUser = validateParameter(Log::LOG_USER, false, PARAMETER_NUMERIC);
if (isset($logUser) && $validation[User::USER_LEVEL] < 1 && $logUser != $validation[User::USER_INDEX]) {
    printOutput(-3, MESSAGE_NOT_ALLOWED);
    exit();
}
$logType = validateParameter(Log::LOG_TYPE, false, PARAMETER_NUMERIC);

# prepare log list result
$logJsonResult = LogManager::getInstance()->getLogs([Log::LOG_INDEX => $logIndex, Log::LOG_PRODUCT => $logProduct, LOG::LOG_USER => $logUser]);

# prepare log type list result
$logTypeJsonResult = LogManager::getInstance()->getLogTypes();


# log list success
printOutput(0, null, [Log::LOG => $logJsonResult, Log::TYPES => $logTypeJsonResult]);

?>
