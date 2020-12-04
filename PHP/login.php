<?php

require_once "./inc.php";

# session auth
if (isset($_REQUEST[UserSession::SESSION])) {
    $session = validateParameter(UserSession::SESSION, true, PARAMETER_STRING);
    $validation = ServiceManager::getInstance()->validateSession($session);
    $user = ServiceManager::getInstance()->loginSession($validation);
} else {
    $userID = validateParameter(User::USER_ID, true, PARAMETER_STRING);
    $userPW = validateParameter(User::USER_PW, true, PARAMETER_STRING);
    $user = ServiceManager::getInstance()->loginAccount([User::USER_ID => $userID, User::USER_PW => $userPW]);
}

$userUUID = validateParameter(User::USER_UUID, false, PARAMETER_STRING);
if (isset($userUUID)) {
    ServiceManager::getInstance()->updateUUID($user, $userUUID);
}

LogManager::getInstance()->addLog([LOG::LOG_USER => $user->getUserIndex(), LOG::LOG_TYPE => LogType::TYPE_LOGIN]);

# user login success
printOutput(0, null, $user->printUser());

?>
