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

$modifyParams = array();
$modifyParams[Product::PRODUCT_INDEX] = $productIndex;
$productGroup = validateParameter(Product::PRODUCT_GROUP, false, PARAMETER_NUMERIC);
if (isset($productGroup)) {
    $modifyParams[Product::PRODUCT_GROUP] = $productGroup;
}
$productName = validateParameter(Product::PRODUCT_NAME, false, PARAMETER_STRING);
if (isset($productName)) {
    $modifyParams[Product::PRODUCT_NAME] = $productName;
}
$productStatus = validateParameter(Product::PRODUCT_STATUS, false, PARAMETER_NUMERIC);
if (isset($productStatus)) {
    $modifyParams[Product::PRODUCT_STATUS] = $productStatus;
}
$productOwner = validateParameter(Product::PRODUCT_OWNER, false, PARAMETER_NUMERIC);
if (isset($productOwner)) {
    $modifyParams[Product::PRODUCT_OWNER] = $productOwner;
}
$productBarcode = validateParameter(Product::PRODUCT_BARCODE, false, PARAMETER_NUMERIC);
if (isset($productBarcode)) {
    $modifyParams[Product::PRODUCT_BARCODE] = $productBarcode;
}

ProductManager::getInstance()->modifyProduct($modifyParams, $validation);

# product modification success
printOutput(0, null, [Product::PRODUCT_INDEX => $productIndex]);

?>
