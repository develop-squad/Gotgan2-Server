<?php

require_once "./inc.php";

# session auth
$session = validateParameter(UserSession::SESSION, true, PARAMETER_STRING);
$validation = ServiceManager::getInstance()->validateSession($session);
ServiceManager::getInstance()->logout($validation[User::USER_SESSION]);

# user logout success
printOutput(0, null);

?>
