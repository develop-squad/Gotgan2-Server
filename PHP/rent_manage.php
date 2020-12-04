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

$key = validateParameter("key", true, PARAMETER_STRING);
if ($key != MANAGER_KEY) {
    printOutput(-3, MESSAGE_KEY_NOT_VALID);
    exit();
}

# prepare rent due list result
$rentDueJsonResult = RentManager::getInstance()->checkRentsDue();

foreach ($rentDueJsonResult as $dueObject) {
    if (isset($dueObject[Rent::RENT_USER_EMAIL])) {
        SystemManager::getInstance()->sendEmail($dueObject[Rent::RENT_USER_EMAIL], Rent::RENT_MESSAGE_DUE_TITLE, Rent::RENT_MESSAGE_DUE_CONTENT);
    }
}

# prepare rent late list result
$rentLateJsonResult = RentManager::getInstance()->checkRentsLate();

foreach ($rentLateJsonResult as $dueObject) {
    if (isset($dueObject[Rent::RENT_USER_EMAIL])) {
        SystemManager::getInstance()->sendEmail($dueObject[Rent::RENT_USER_EMAIL], Rent::RENT_MESSAGE_LATE_TITLE, Rent::RENT_MESSAGE_LATE_CONTENT);
    }
}

# rent manage success
printOutput(0, null);

?>