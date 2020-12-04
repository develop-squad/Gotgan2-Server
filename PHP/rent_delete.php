<?php

require_once "./inc.php";

if (!SystemManager::getInstance()->checkSystemMaster()) {
    printOutput(-3, MESSAGE_SYSTEM_OFF);
    exit();
}

if (!SystemManager::getInstance()->checkSystemRent()) {
    printOutput(-3, MESSAGE_RENT_OFF);
    exit();
}

$session = validateParameter(UserSession::SESSION, true, PARAMETER_STRING);
$validation = ServiceManager::getInstance()->validateSession($session);

$rentIndex = validateParameter(Rent::RENT_INDEX, true, PARAMETER_NUMERIC);

$rentRequestData = RentManager::getInstance()->getRentRequest([Rent::RENT_INDEX => $rentIndex]);

if ($validation[User::USER_LEVEL] < 1 && ($rentRequestData[Rent::RENT_STATUS] != 1 || $rentRequestData[Rent::RENT_USER] != $validation[User::USER_INDEX])) {
    printOutput(-3, MESSAGE_NOT_ALLOWED);
    exit();
}

LogManager::getInstance()->deleteRent($rentRequestData, $validation);

# rent deletion success
printOutput(0, null);

?>
