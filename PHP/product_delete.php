<?php

require_once "./inc.php";

if (!SystemManager::getInstance()->checkSystemMaster()) {
    printOutput(-3, MESSAGE_SYSTEM_OFF);
    exit();
}

$session = validateParameter(UserSession::SESSION, true, PARAMETER_STRING);
$validation = ServiceManager::getInstance()->validateSession($session);
if ($validation[User::USER_LEVEL] < 1) {
    printOutput(-3, MESSAGE_NOT_ALLOWED);
    exit();
}

$productIndex = validateParameter(Product::PRODUCT_INDEX, true, PARAMETER_NUMERIC);

ProductManager::getInstance()->deleteProduct($productIndex, $validation);

# user group deletion success
printOutput(0, null);

?>
