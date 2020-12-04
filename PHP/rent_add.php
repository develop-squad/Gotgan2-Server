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

$productIndex = validateParameter(Rent::RENT_PRODUCT, false, PARAMETER_NUMERIC);
if (isset($productIndex)) {
    ProductManager::getInstance()->checkProductRentAvailable([Product::PRODUCT_INDEX => $productIndex]);
}

$productBarcode = validateParameter(Product::PRODUCT_BARCODE, false, PARAMETER_NUMERIC);
if (isset($productBarcode)) {
    $productIndex = ProductManager::getInstance()->checkProductRentAvailable([Product::PRODUCT_BARCODE => $productBarcode]);
}

$rentUser = validateParameter(Rent::RENT_USER, false, PARAMETER_NUMERIC, $validation[User::USER_INDEX]);
if ($validation[User::USER_LEVEL] < 1 && $rentUser != $validation[User::USER_INDEX]) {
    printOutput(-3, MESSAGE_NOT_ALLOWED);
    exit();
}

$rentTimeStart = validateParameter(Rent::RENT_TIME_START, true, PARAMETER_STRING);
try {
    $timeValid = date_create_from_format('Y-m-d H:i:s', $rentTimeStart);
    if (!$timeValid) {
        printOutput(-1, MESSAGE_DATE_NOT_VALID);
        exit();
    }
} catch (Exception $e) {
    printOutput(-1, MESSAGE_DATE_NOT_VALID);
    exit();
}

$rentIndex = RentManager::getInstance()->addRent([Rent::RENT_USER => $rentUser, Rent::RENT_PRODUCT => $productIndex, Rent::RENT_TIME_START => $rentTimeStart], $validation);

# rent creation success
printOutput(0, null, [Rent::RENT_INDEX => $rentIndex]);

?>
