<?php

require_once "./inc.php";

if (!SystemManager::getInstance()->checkSystemMaster()) {
    printOutput(-3, MESSAGE_SYSTEM_OFF);
    exit();
}

$userIndex = validateParameter(User::USER_INDEX, true, PARAMETER_NUMERIC);

$session = validateParameter(UserSession::SESSION, true, PARAMETER_STRING);
$validation = ServiceManager::getInstance()->validateSession($session);
if ($validation[User::USER_LEVEL] < 1 && $validation[User::USER_INDEX != $userIndex]) {
    printOutput(-3, MESSAGE_NOT_ALLOWED);
    exit();
}

$modifyParams = array();
$modifyParams[User::USER_INDEX] = $userIndex;

$userID = validateParameter(User::USER_ID, false, PARAMETER_STRING);
if (isset($userID)) {
    $modifyParams[User::USER_ID] = $userID;
}

$userPW = validateParameter(User::USER_PW, false, PARAMETER_STRING);
if (isset($userPW)) {
    $userPW = password_hash($userPW, PASSWORD_BCRYPT);
    $modifyParams[User::USER_PW] = $userPW;
}

$userLevel = validateParameter(User::USER_LEVEL, false, PARAMETER_NUMERIC);
if (isset($userLevel)) {
    if ($userLevel > $validation[User::USER_LEVEL]) {
        printOutput(-3, MESSAGE_NOT_ALLOWED);
        exit();
    }
    $modifyParams[User::USER_LEVEL] = $userLevel;
}

$userName = validateParameter(User::USER_NAME, false, PARAMETER_STRING);
if (isset($userName)) {
    $modifyParams[User::USER_NAME] = $userName;
}

$userGroup = validateParameter(User::USER_GROUP, false, PARAMETER_NUMERIC);
if (isset($userGroup)) {
    if ($validation[User::USER_LEVEL < 1]) {
        printOutput(-3, MESSAGE_NOT_ALLOWED);
        exit();
    }
    $modifyParams[User::USER_GROUP] = $userGroup;
}

$userSid = validateParameter(User::USER_SID, false, PARAMETER_STRING);
if (isset($userSid)) {
    $modifyParams[User::USER_SID] = $userSid;
}

$userBlock = validateParameter(User::USER_BLOCK, false, PARAMETER_NUMERIC);
if (isset($userBlock)) {
    $modifyParams[User::USER_BLOCK] = $userBlock;
}

$userUUID = validateParameter(User::USER_UUID, false, PARAMETER_STRING);
if (isset($userUUID)) {
    $modifyParams[User::USER_UUID] = $userUUID;
}

$userEmail = validateParameter(User::USER_EMAIL, false, PARAMETER_STRING);
if (isset($userEmail)) {
    $modifyParams[User::USER_EMAIL] = $userEmail;
}

$userPhone = validateParameter(User::USER_PHONE, false, PARAMETER_STRING);
if (isset($userPhone)) {
    $modifyParams[User::USER_PHONE] = $userPhone;
}

UserManager::getInstance()->modifyUser($modifyParams, $validation);

# user modification success
printOutput(0, null, [User::USER_INDEX, $userIndex]);

?>
