<?php

require_once "./inc.php";

if (!SystemManager::getInstance()->checkSystemMaster()) {
    printOutput(-3, MESSAGE_SYSTEM_OFF);
    exit();
}

$session = validateParameter(UserSession::SESSION, true, PARAMETER_STRING);
$validation = ServiceManager::getInstance()->validateSession($session);

# prepare product group list result
$productGroupJsonResult = ProductManager::getInstance()->printProductOverview();

# product group overview success
printOutput(0, null, [ProductGroup::GROUPS => $productGroupJsonResult]);

?>
