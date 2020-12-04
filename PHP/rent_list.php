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

$rentIndex = validateParameter(Rent::RENT_INDEX, false, PARAMETER_NUMERIC);
$rentUser = validateParameter(Rent::RENT_USER, false, PARAMETER_NUMERIC);
$rentProduct = validateParameter(Rent::RENT_PRODUCT, false, PARAMETER_NUMERIC);
$productBarcode = validateParameter(Product::PRODUCT_BARCODE, false, PARAMETER_NUMERIC);
$rentStatus = validateParameter(Rent::RENT_STATUS, false, PARAMETER_NUMERIC);
$rentDelayed = validateParameter(Rent::RENT_DELAYED, false, PARAMETER_NUMERIC);

# prepare rent list result
$rentJsonResult = RentManager::getInstance()->getRents([Rent::RENT_INDEX => $rentIndex, Rent::RENT_USER => $rentUser, Rent::RENT_PRODUCT => $rentProduct, Product::PRODUCT_BARCODE => $productBarcode, Rent::RENT_STATUS => $rentStatus, Rent::RENT_DELAYED => $rentDelayed]);

# rent list success
printOutput(0, null, [Rent::RENT => $rentJsonResult]);

?>
