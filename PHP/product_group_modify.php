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

$productGroupIndex = validateParameter(ProductGroup::PRODUCT_GROUP_INDEX, true, PARAMETER_NUMERIC);

$modifyParams = array();
$modifyParams[ProductGroup::PRODUCT_GROUP_INDEX] = $productGroupIndex;
$productGroupName = validateParameter(ProductGroup::PRODUCT_GROUP_NAME, false, PARAMETER_STRING);
if (isset($productGroupName)) {
    $modifyParams[ProductGroup::PRODUCT_GROUP_NAME] = $productGroupName;
}
$productGroupRentable = validateParameter(ProductGroup::PRODUCT_GROUP_RENTABLE, false, PARAMETER_NUMERIC);
if (isset($productGroupRentable)) {
    $modifyParams[ProductGroup::PRODUCT_GROUP_RENTABLE] = $productGroupRentable;
}
$productGroupPriority = validateParameter(ProductGroup::PRODUCT_GROUP_PRIORITY, false, PARAMETER_NUMERIC);
if (isset($productGroupPriority)) {
    $modifyParams[ProductGroup::PRODUCT_GROUP_PRIORITY] = $productGroupPriority;
}

ProductManager::getInstance()->modifyProductGroup($modifyParams, $validation);

# product modification success
printOutput(0, null, [ProductGroup::PRODUCT_GROUP_INDEX, $productGroupIndex]);

?>
