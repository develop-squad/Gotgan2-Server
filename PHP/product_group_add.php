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

$productGroupName = validateParameter(ProductGroup::PRODUCT_GROUP_NAME, true, PARAMETER_STRING);
$productGroupRentable = validateParameter(ProductGroup::PRODUCT_GROUP_RENTABLE, true, PARAMETER_NUMERIC);
$productGroupPriority = validateParameter(ProductGroup::PRODUCT_GROUP_PRIORITY, false, PARAMETER_NUMERIC, 0);


$productGroupIndex = ProductManager::getInstance()->addProductGroup([ProductGroup::PRODUCT_GROUP_NAME => $productGroupName, ProductGroup::PRODUCT_GROUP_RENTABLE => $productGroupRentable, ProductGroup::PRODUCT_GROUP_PRIORITY => $productGroupPriority], $validation);

# product group creation success
printOutput(0, null, [ProductGroup::PRODUCT_GROUP_INDEX => $productGroupIndex]);

?>
