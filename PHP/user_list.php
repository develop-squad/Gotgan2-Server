<?php

require_once "./inc.php";

if (!SystemManager::getInstance()->checkSystemMaster()) {
    printOutput(-3, MESSAGE_SYSTEM_OFF);
    exit();
}

$userIndex = validateParameter(User::USER_INDEX, false, PARAMETER_NUMERIC);
$userGroup = validateParameter(User::USER_GROUP, false, PARAMETER_NUMERIC);

$session = validateParameter(UserSession::SESSION, true, PARAMETER_STRING);
$validation = ServiceManager::getInstance()->validateSession($session);
if ($validation[User::USER_LEVEL] < 1) {
    # prevent normal user try to show other user info
    if (!isset($userIndex) || $userIndex != $validation[User::USER_INDEX])
    {
        printOutput(-3, MESSAGE_NOT_ALLOWED);
        exit();
    }
}

# prepare user list result
$userJsonResult = UserManager::getInstance()->getUsers([User::USER_INDEX => $userIndex, User::USER_GROUP => $userGroup], $validation);

# prepare user group list result
$userGroupJsonResult = UserManager::getInstance()->getUserGroups($validation);

# log list success
printOutput(0, "", [User::USER => $userJsonResult, UserGroup::GROUPS => $userGroupJsonResult]);

?>
