<?php

require_once "./inc.php";

if (!SystemManager::getInstance()->checkSystemMaster()) {
    printOutput(-3, MESSAGE_SYSTEM_OFF);
    exit();
}

$key = validateParameter(KEY_MANAGE, true, PARAMETER_STRING);
if ($key != MANAGER_KEY) {
    printOutput(-3, MESSAGE_KEY_NOT_VALID);
    exit();
}

HistoryManager::getInstance()->checkHistories();

# history manage success
printOutput(0, null);

?>