<?php

require_once "./inc.php";

if (!SystemManager::getInstance()->checkSystemMaster()) {
    printOutput(-3, MESSAGE_SYSTEM_OFF);
    exit();
}

$session = validateParameter(UserSession::SESSION, true, PARAMETER_STRING);
$validation = ServiceManager::getInstance()->validateSession($session);

$searchParams = array();
$productIndex = validateParameter(Product::PRODUCT_INDEX, false, PARAMETER_NUMERIC);
if (isset($productIndex)) {
    $searchParams[Product::PRODUCT_INDEX] = $productIndex;
}
$productBarcode = validateParameter(Product::PRODUCT_BARCODE, false, PARAMETER_NUMERIC);
if (isset($productBarcode)) {
    $searchParams[Product::PRODUCT_BARCODE] = $productBarcode;
}
$productGroup = validateParameter(Product::PRODUCT_GROUP, false, PARAMETER_NUMERIC);
if (isset($productGroup)) {
    $searchParams[Product::PRODUCT_GROUP] = $productGroup;
}

# prepare product list result
$productJsonResult = ProductManager::getInstance()->getProducts($searchParams);

# prepare product group list result
$productGroupJsonResult = ProductManager::getInstance()->getProductGroups($searchParams);

# product list success
printOutput(0, "", [Product::PRODUCT => $productJsonResult, ProductGroup::GROUPS => $productGroupJsonResult]);

?>
