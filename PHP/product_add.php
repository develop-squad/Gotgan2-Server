<?php

require_once "./inc.php";

if (!SystemManager::getInstance()->checkSystemMaster()) {
    printOutput(-3, MESSAGE_SYSTEM_OFF);
    exit();
}

$session = validateParameter(UserSession::SESSION, true, PARAMETER_STRING);
$validation = ServiceManager::getInstance()->validateSession($session);

$products = validateParameter(Product::PRODUCT, true, PARAMETER_STRING);

ProductManager::getInstance()->addProducts($products, $validation);

# product creation success
printOutput(0, null);

?>
