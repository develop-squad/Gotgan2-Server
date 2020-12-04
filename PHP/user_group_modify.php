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
$modifyParams = array();
$modifyParams[UserGroup::USER_GROUP_INDEX] = $groupIndex;
$groupName = validateParameter(UserGroup::USER_GROUP_NAME, false, PARAMETER_STRING);
if (isset($groupName)) {
    $modifyParams[UserGroup::USER_GROUP_NAME] = $groupName;
}

UserManager::getInstance()->modifyUserGroup($modifyParams, $validation);

# user modification success
printOutput(0, null, [UserGroup::USER_GROUP_INDEX => $groupIndex]);

?>
