<?php

require_once "./inc.php";

if (!SystemManager::getInstance()->checkSystemMaster()) {
    printOutput(-3, MESSAGE_SYSTEM_OFF);
    exit();
}

$userID = validateParameter(User::USER_ID, true, PARAMETER_STRING);
$userPW = validateParameter(User::USER_PW, true, PARAMETER_STRING);
$userPW = password_hash($userPW, PASSWORD_BCRYPT);
$userLevel = validateParameter(User::USER_LEVEL, true, PARAMETER_NUMERIC);
$userName = validateParameter(User::USER_NAME, true, PARAMETER_STRING);
$userGroup = validateParameter(User::USER_GROUP, false, PARAMETER_NUMERIC, 0);
$userSID = validateParameter(User::USER_SID, false, PARAMETER_STRING);
$userBlock = validateParameter(User::USER_BLOCK, false, PARAMETER_NUMERIC, 0);
$userUUID = validateParameter(User::USER_UUID, false, PARAMETER_STRING);
$userEmail = validateParameter(User::USER_EMAIL, false, PARAMETER_STRING);
$userPhone = validateParameter(User::USER_PHONE, false, PARAMETER_STRING);

$session = validateParameter(UserSession::SESSION, false, PARAMETER_STRING);
if (isset($session)) {
    $validation = ServiceManager::getInstance()->validateSession($session);
    if ($validation[User::USER_LEVEL] < 1) {
        printOutput(-3, MESSAGE_NOT_ALLOWED);
        exit();
    }
} else {
    if ($userLevel > 0) {
        printOutput(-3, MESSAGE_NOT_ALLOWED);
        exit();
    }
}

$userIndex = UserManager::getInstance()->addUser([User::USER_ID => $userID, User::USER_PW => $userPW, User::USER_LEVEL => $userLevel, User::USER_NAME => $userName, User::USER_GROUP => $userGroup, User::USER_SID => $userSID, User::USER_BLOCK => $userBlock, User::USER_UUID => $userUUID, User::USER_EMAIL => $userEmail, User::USER_PHONE => $userPhone], $validation);

# user creation success
printOutput(0, null, [User::USER_INDEX => $userIndex]);

?>
