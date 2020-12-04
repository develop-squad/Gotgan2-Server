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

$groupName = validateParameter(UserGroup::USER_GROUP_NAME, true, PARAMETER_STRING);

$groupIndex = UserManager::getInstance()->addUserGroup([UserGroup::USER_GROUP_NAME => $groupName], $validation);

# user group creation success
printOutput(0, null, [UserGroup::USER_GROUP_INDEX => $groupIndex]);

?>
