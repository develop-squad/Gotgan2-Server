<?php

require_once "./inc.php";

if (!SystemManager::getInstance()->checkSystemMaster()) {
    printOutput(-3, MESSAGE_SYSTEM_OFF);
    exit();
}

$session = validateParameter(UserSession::SESSION, false, PARAMETER_STRING);
$validation = ServiceManager::getInstance()->validateSession($session);
if ($validation[User::USER_LEVEL] < 1) {
    printOutput(-3, MESSAGE_NOT_ALLOWED);
    exit();
}

$groupIndex = validateParameter(UserGroup::USER_GROUP_INDEX, true, PARAMETER_NUMERIC);

UserManager::getInstance()->deleteUserGroup([UserGroup::USER_GROUP_INDEX => $groupIndex], $validation);

# user group deletion success
printOutput(0, null);

?>
