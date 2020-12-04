<?php

require_once "./inc.php";

if (!SystemManager::getInstance()->checkSystemMaster()) {
    printOutput(-3, MESSAGE_SYSTEM_OFF);
    exit();
}

if (!SystemManager::getInstance()->checkSystemRent()) {
    printOutput(-3, MESSAGE_RENT_OFF);
    exit();
}

$session = validateParameter(UserSession::SESSION, true, PARAMETER_STRING);
$validation = ServiceManager::getInstance()->validateSession($session);
if ($validation[User::USER_LEVEL] < 1) {
    printOutput(-3, MESSAGE_NOT_ALLOWED);
    exit();
}

$rentIndex = validateParameter(Rent::RENT_INDEX, false, PARAMETER_NUMERIC, -1);
$productBarcode = validateParameter(Product::PRODUCT_BARCODE, false, PARAMETER_NUMERIC, -1);

if ($rentIndex == -1 && $productBarcode == -1) {
    printOutput(-1, MESSAGE_RENT_TARGET_EMPTY);
    exit();
}

$rentRequestData = RentManager::getInstance()->getRentRequest([Rent::RENT_INDEX => $rentIndex, Product::PRODUCT_BARCODE => $productBarcode]);

RentManager::getInstance()->allowRent($rentRequestData);

# rent allow success
printOutput(0, null);

?>
