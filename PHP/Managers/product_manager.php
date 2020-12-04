<?php

class ProductManager extends Singleton {

    protected static $instance;

    public function getProducts($params) {
        # prepare product list result
        $productJsonResult = array();

        # execute product list query
        try {
            $dbQuery = "
                SELECT `P`.`product_index`, 
                    `P`.`product_group` as `product_group_index`,
                    `PG`.`product_group_name` as `product_group_name`, 
                    `PG`.`product_group_priority` as `product_group_priority`,
                    `P`.`product_name`, `P`.`product_status`,
                    `P`.`product_owner` as `product_owner_index`,
                    `UG`.`user_group_name` as `product_owner_name`,
                    `P`.`product_rent` as `product_rent_index`,
                    `R`.`rent_user` as `product_rent_user_index`,
                    `U`.`user_name` as `product_rent_user_name`,
                    `U`.`user_id` as `product_rent_user_id`,
                    `R`.`rent_status` as `product_rent_status`,
                    `R`.`rent_time_start` as `product_rent_start`,
                    `R`.`rent_time_end` as `product_rent_end`,
                    `R`.`rent_time_return` as `product_rent_return`,
                    `P`.`product_barcode`, `P`.`product_created`
                FROM `Products` AS `P`
                    LEFT OUTER JOIN `UserGroup` AS `UG` ON (`P`.`product_owner` = `UG`.`user_group_index`) 
                    LEFT OUTER JOIN `ProductGroup` AS `PG` ON (`P`.`product_group` = `PG`.`product_group_index`) 
                    LEFT OUTER JOIN `Rents` AS `R` ON (`P`.`product_rent` = `R`.`rent_index`) 
                    LEFT OUTER JOIN `Users` AS `U` ON (`R`.`rent_user` = `U`.`user_index`)
                WHERE 1=1";
            if (isset($params[Product::PRODUCT_INDEX])) {
                $dbQuery .= " AND `product_index` = ".$params[Product::PRODUCT_INDEX];
            }
            if (isset($params[Product::PRODUCT_BARCODE])) {
                $dbQuery .= " AND `product_barcode` = ".$params[Product::PRODUCT_BARCODE];
            }
            if (isset($params[Product::PRODUCT_GROUP])) {
                $dbQuery .= " AND `product_group` = ".$params[Product::PRODUCT_GROUP];
            }
            $db = DatabaseManager::getInstance()->getConnection();
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # product list query error
                printOutput(-4, "PRODUCT LIST FAILURE : ".$dbStatement->error);
                exit();
            }
            $productData = array();
            $dbStatement->bind_result($productData[Product::PRODUCT_INDEX], $productData[ProductGroup::PRODUCT_GROUP_INDEX], $productData[ProductGroup::PRODUCT_GROUP_NAME], $productData[ProductGroup::PRODUCT_GROUP_PRIORITY], $productData[Product::PRODUCT_NAME], $productData[Product::PRODUCT_STATUS], $productData[UserGroup::USER_GROUP_INDEX], $productData[UserGroup::USER_GROUP_NAME],
                $productData[Product::PRODUCT_RENT], $productData[Rent::RENT_USER], $productData[User::USER_NAME], $productData[User::USER_ID], $productData[Rent::RENT_STATUS], $productData[Rent::RENT_TIME_START], $productData[Rent::RENT_TIME_END], $productData[Rent::RENT_TIME_RETURN], $productData[Product::PRODUCT_BARCODE], $productData[Product::PRODUCT_CREATED]);
            while($dbStatement->fetch()) {
                $productJsonObject = array();
                $productJsonObject["product_index"] = $productData[Product::PRODUCT_INDEX];
                $productJsonObject["product_group_index"] = $productData[ProductGroup::PRODUCT_GROUP_INDEX];
                if (isset($productData[ProductGroup::PRODUCT_GROUP_NAME])) {
                    $productJsonObject["product_group_name"] = $productData[ProductGroup::PRODUCT_GROUP_NAME];
                } else {
                    $productJsonObject["product_group_name"] = "";
                }
                if (isset($productData[ProductGroup::PRODUCT_GROUP_PRIORITY])) {
                    $productJsonObject["product_group_priority"] = $productData[ProductGroup::PRODUCT_GROUP_PRIORITY];
                } else {
                    $productJsonObject["product_group_priority"] = 0;
                }
                $productJsonObject["product_name"] = $productData[Product::PRODUCT_NAME];
                $productJsonObject["product_status"] = $productData[Product::PRODUCT_STATUS];
                $productJsonObject["product_owner_index"] = $productData[UserGroup::USER_GROUP_INDEX];
                if (isset($productData[UserGroup::USER_GROUP_NAME])) {
                    $productJsonObject["product_owner_name"] = $productData[UserGroup::USER_GROUP_NAME];
                } else {
                    $productJsonObject["product_owner_name"] = "";
                }
                $productJsonObject["product_rent_index"] = $productData[Product::PRODUCT_RENT];
                if (isset($productData[Rent::RENT_USER])) {
                    $productJsonObject["product_rent_user_index"] = $productData[Rent::RENT_USER];
                }
                if (isset($productData[User::USER_NAME])) {
                    $productJsonObject["product_rent_user_name"] = $productData[User::USER_NAME];
                }
                if (isset($productData[User::USER_ID])) {
                    $productJsonObject["product_rent_user_id"] = $productData[User::USER_ID];
                }
                if (isset($productData[Rent::RENT_STATUS])) {
                    $productJsonObject["product_rent_status"] = $productData[Rent::RENT_STATUS];
                }
                if (isset($productData[Rent::RENT_TIME_START])) {
                    $productJsonObject["product_rent_time_start"] = $productData[Rent::RENT_TIME_START];
                }
                if (isset($productData[Rent::RENT_TIME_END])) {
                    $productJsonObject["product_rent_time_end"] = $productData[Rent::RENT_TIME_END];
                }
                if (isset($productData[Rent::RENT_TIME_RETURN])) {
                    $productJsonObject["product_rent_time_return"] = $productData[Rent::RENT_TIME_RETURN];
                }
                $productJsonObject["product_barcode"] = $productData[Product::PRODUCT_BARCODE];
                $productJsonObject["product_created"] = $productData[Product::PRODUCT_CREATED];
                array_push($productJsonResult, $productJsonObject);
            }
            $dbStatement->close();
        } catch(Exception $e) {
            # product list query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        return $productJsonResult;
    }

    public function checkProductRentAvailable($params) {
        # execute product detail query
        try {
            $dbQuery = "SELECT `product_index`, `product_status`, `product_rent`, `product_group_rentable` FROM `Products` AS `P` LEFT OUTER JOIN `ProductGroup` AS `PG` ON (`P`.`product_group` = `PG`.`product_group_index`) WHERE 1 = 1";
            if (isset($params[Product::PRODUCT_INDEX])) {
                $dbQuery .= " AND `product_index` = ".$params[Product::PRODUCT_INDEX];
            }
            if (isset($params[Product::PRODUCT_BARCODE])) {
                $dbQuery .= " AND `product_barcode` = ".$params[Product::PRODUCT_BARCODE];
            }
            $db = DatabaseManager::getInstance()->getConnection();
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : " . $db->error);
                exit();
            }
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # product detail query error
                printOutput(-2, "PRODUCT DETAIL FAILURE : " . $dbStatement->error);
                exit();
            }
            $productRentData = array();
            $dbStatement->bind_result($productRentData[Product::PRODUCT_INDEX], $productRentData[Product::PRODUCT_STATUS], $productRentData[Product::PRODUCT_RENT], $productRentData[ProductGroup::PRODUCT_GROUP_RENTABLE]);
            $dbStatement->fetch();
            if ($productRentData[Product::PRODUCT_STATUS] != 0 || $productRentData[Product::PRODUCT_RENT] != 0 || $productRentData[ProductGroup::PRODUCT_GROUP_RENTABLE] == 0) {
                # product is on rent
                printOutput(-3, "NOT VALID PRODUCT");
                exit();
            }
            $dbStatement->close();
        } catch (Exception $e) {
            # product detail query error
            printOutput(-2, "PRODUCT DETAIL FAILURE : " . $dbStatement->error);
            exit();
        }

        return $productRentData[Product::PRODUCT_INDEX];
    }

    public function addProduct($product) {

    }

    public function addProducts($products, $validation = null) {
        # decode product json array
        try {
            $productArray = json_decode($products, true);
            if (count($productArray) < 1) {
                printOutput(-1, "products MUST HAVE DATA");
                exit();
            }
        } catch (JsonException $e) {
            printOutput(-1, "products MUST BE JSON ARRAY : ".$e->getMessage());
            exit();
        }

        # prepare product group list result
        $productGroupResult = array();

        # execute product group list query
        try {
            $dbQuery = "SELECT `product_group_index`, `product_group_name` FROM `ProductGroup`";
            $db = DatabaseManager::getInstance()->getConnection();
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # product group list query error
                printOutput(-4, "PRODUCT GROUP LIST FAILURE : ".$dbStatement->error);
                exit();
            }
            $productGroupIndex = 0;
            $productGroupName = 0;
            $dbStatement->bind_result($productGroupIndex, $productGroupName);
            while($dbStatement->fetch()) {
                $productGroupResult[$productGroupIndex] = $productGroupName;
            }
            $dbStatement->close();
        } catch(Exception $e) {
            # product group list query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        # change products empty values to default
        for ($i = 0; $i < count($productArray); $i++) {
            if (!isset($productArray[$i]["product_group"])) {
                printOutput(-1, "products MUST HAVE product_group");
                exit();
            }
            if (!isset($productArray[$i]["product_name"])) {
                $productArray[$i]["product_name"] = $productGroupResult[$productArray[$i]["product_group"]];
            }
            if (!isset($productArray[$i]["product_status"])) {
                $productArray[$i]["product_status"] = 0;
            }
            if (!isset($productArray[$i]["product_owner"])) {
                $productArray[$i]["product_owner"] = 0;
            }
            if (!isset($productArray[$i]["product_rent"])) {
                $productArray[$i]["product_rent"] = 0;
            }
            if (!isset($productArray[$i]["product_barcode"])) {
                $productArray[$i]["product_barcode"] = 0;
            }
        }

        # execute product creation query
        try {
            $dbQuery = "INSERT INTO `Products` (`product_group`, `product_name`, `product_status`, `product_owner`, `product_rent`, `product_barcode`) VALUES (?, ?, ?, ?, ?, ?)";
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : " . $db->error);
                exit();
            }
            for ($i = 0; $i < count($productArray); $i++) {
                $dbStatement->bind_param("isiiii", $productArray[$i]["product_group"], $productArray[$i]["product_name"], $productArray[$i]["product_status"], $productArray[$i]["product_owner"], $productArray[$i]["product_rent"], $productArray[$i]["product_barcode"]);
                $dbStatement->execute();
                if ($dbStatement->errno != 0) {
                    # product creation query error
                    printOutput(-2, "DB QUERY FAILURE : ".$dbStatement->error);
                    exit();
                }
                if (isset($validation)) {
                    LogManager::getInstance()->addLog([LOG::LOG_PRODUCT => $dbStatement->insert_id, LOG::LOG_USER => $validation[User::USER_INDEX], LOG::LOG_TYPE => LogType::TYPE_PRODUCT_ADD]);
                }
            }
            $dbStatement->close();
        } catch (Exception $e) {
            # product creation query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }
    }

    public function deleteProduct($productIndex, $validation = null) {
        # execute product deletion query
        try {
            $dbQuery = "DELETE FROM `Products` WHERE `product_index` = ?";
            $db = DatabaseManager::getInstance()->getConnection();
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->bind_param("i", $productIndex);
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # product deletion query error
                printOutput(-4, "DELETE PRODUCT FAILURE : ".$dbStatement->error);
                exit();
            }
            if (isset($validation)) {
                LogManager::getInstance()->addLog([LOG::LOG_PRODUCT => $productIndex, LOG::LOG_USER => $validation[User::USER_INDEX], LOG::LOG_TYPE => LogType::TYPE_PRODUCT_DELETE]);
            }
            $dbStatement->close();
        } catch(Exception $e) {
            # product deletion query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }
    }

    private function executeModifyProduct(int $productIndex, $modifyQuery, string $bindType, $bindValue, $validation = null) {
        # execute product modification query
        try {
            $db = DatabaseManager::getInstance()->getConnection();
            $dbStatement = $db->prepare($modifyQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->bind_param($bindType, $bindValue, $productIndex);
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # product group modification query error
                printOutput(-4, "MODIFY PRODUCT FAILURE : ".$dbStatement->error);
                exit();
            }
            $dbStatement->close();
        } catch(Exception $e) {
            # product group modification query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }
    }

    public function modifyProduct($params, $validation = null) {
        if (!isset($params[Product::PRODUCT_INDEX])) {
            return;
        }
        if (isset($params[Product::PRODUCT_GROUP])) {
            $groupModifyQuery = "UPDATE `Products` SET `product_group` = ? WHERE `product_index` = ?";
            self::executeModifyProduct($params[Product::PRODUCT_INDEX], $groupModifyQuery, "ii", $params[Product::PRODUCT_GROUP]);
        }
        if (isset($params[Product::PRODUCT_NAME])) {
            $nameModifyQuery = "UPDATE `Products` SET `product_name` = ? WHERE `product_index` = ?";
            self::executeModifyProduct($params[Product::PRODUCT_INDEX], $nameModifyQuery, "si", $params[Product::PRODUCT_NAME]);
        }
        if (isset($params[Product::PRODUCT_STATUS])) {
            $statusModifyQuery = "UPDATE `Products` SET `product_status` = ? WHERE `product_index` = ?";
            self::executeModifyProduct($params[Product::PRODUCT_INDEX], $statusModifyQuery, "ii", $params[Product::PRODUCT_STATUS]);
        }
        if (isset($params[Product::PRODUCT_OWNER])) {
            $ownerModifyQuery = "UPDATE `Products` SET `product_owner` = ? WHERE `product_index` = ?";
            self::executeModifyProduct($params[Product::PRODUCT_INDEX], $ownerModifyQuery, "ii", $params[Product::PRODUCT_OWNER]);
        }
        if (isset($params[Product::PRODUCT_BARCODE])) {
            $barcodeModifyQuery = "UPDATE `Products` SET `product_barcode` = ? WHERE `product_index` = ?";
            self::executeModifyProduct($params[Product::PRODUCT_INDEX], $barcodeModifyQuery, "ii", $params[Product::PRODUCT_BARCODE]);
        }
        if (isset($validation)) {
            LogManager::getInstance()->addLog([LOG::LOG_PRODUCT => $params[Product::PRODUCT_INDEX], LOG::LOG_USER => $validation[User::USER_INDEX], LOG::LOG_TYPE => LogType::TYPE_PRODUCT_MODIFY]);
        }
    }

    public function printProductOverview() {
        # prepare product group list result
        $productGroupJsonResult = self::getProductGroups();

        # execute product group overview - available query
        try {
            $dbQuery = "SELECT `product_group`, COUNT(`product_index`) FROM `Products` WHERE `product_status` = 0 GROUP BY `product_group`";
            $db = DatabaseManager::getInstance()->getConnection();
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # product group overview query error
                printOutput(-4, "PRODUCT GROUP LIST FAILURE : ".$dbStatement->error);
                exit();
            }
            $productGroupAvailableData = array();
            $dbStatement->bind_result($productGroupAvailableData[ProductGroup::GROUP_INDEX], $productGroupAvailableData[ProductGroup::GROUP_COUNT_AVAILABLE]);
            while($dbStatement->fetch()) {
                foreach ($productGroupJsonResult as &$productGroupObject) {
                    if ($productGroupObject[ProductGroup::GROUP_INDEX] == $productGroupAvailableData[ProductGroup::GROUP_INDEX]) {
                        $productGroupObject[ProductGroup::GROUP_COUNT_AVAILABLE] = $productGroupAvailableData[ProductGroup::GROUP_COUNT_AVAILABLE];
                        break;
                    }
                }
                unset($productGroupObject);
            }
            $dbStatement->close();
        } catch(Exception $e) {
            # product group overview query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        # execute product group overview - unavailable query
        try {
            $dbQuery = "SELECT `product_group`, COUNT(`product_index`) FROM `Products` WHERE `product_status` = 1 GROUP BY `product_group`";
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # product group overview query error
                printOutput(-4, "PRODUCT GROUP LIST FAILURE : ".$dbStatement->error);
                exit();
            }
            $productGroupUnavailableData = array();
            $dbStatement->bind_result($productGroupUnavailableData[ProductGroup::GROUP_INDEX], $productGroupUnavailableData[ProductGroup::GROUP_COUNT_UNAVAILABLE]);
            while($dbStatement->fetch()) {
                foreach ($productGroupJsonResult as &$productGroupObject) {
                    if ($productGroupObject[ProductGroup::GROUP_INDEX] == $productGroupUnavailableData[ProductGroup::GROUP_INDEX]) {
                        $productGroupObject[ProductGroup::GROUP_COUNT_UNAVAILABLE] = $productGroupUnavailableData[ProductGroup::GROUP_COUNT_UNAVAILABLE];
                        break;
                    }
                }
                unset($productGroupObject);
            }
            $dbStatement->close();
        } catch(Exception $e) {
            # product group overview query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        # execute product group overview - broken query
        try {
            $dbQuery = "SELECT `product_group`, COUNT(`product_index`) FROM `Products` WHERE `product_status` = 2 GROUP BY `product_group`";
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # product group overview query error
                printOutput(-4, "PRODUCT GROUP LIST FAILURE : ".$dbStatement->error);
                exit();
            }
            $productGroupBrokenData = array();
            $dbStatement->bind_result($productGroupBrokenData[ProductGroup::GROUP_INDEX], $productGroupBrokenData[ProductGroup::GROUP_COUNT_BROKEN]);
            while($dbStatement->fetch()) {
                foreach ($productGroupJsonResult as &$productGroupObject) {
                    if ($productGroupObject[ProductGroup::GROUP_INDEX] == $productGroupBrokenData[ProductGroup::GROUP_INDEX]) {
                        $productGroupObject[ProductGroup::GROUP_COUNT_BROKEN] = $productGroupBrokenData[ProductGroup::GROUP_COUNT_BROKEN];
                        break;
                    }
                }
                unset($productGroupObject);
            }
            $dbStatement->close();
        } catch(Exception $e) {
            # product group overview query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        # execute product group overview - repair query
        try {
            $dbQuery = "SELECT `product_group`, COUNT(`product_index`) FROM `Products` WHERE `product_status` = 3 GROUP BY `product_group`";
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # product group overview query error
                printOutput(-4, "PRODUCT GROUP LIST FAILURE : ".$dbStatement->error);
                exit();
            }
            $productGroupRepairData = array();
            $dbStatement->bind_result($productGroupRepairData[ProductGroup::GROUP_INDEX], $productGroupRepairData[ProductGroup::GROUP_COUNT_REPAIR]);
            while($dbStatement->fetch()) {
                foreach ($productGroupJsonResult as &$productGroupObject) {
                    if ($productGroupObject[ProductGroup::GROUP_INDEX] == $productGroupRepairData[ProductGroup::GROUP_INDEX]) {
                        $productGroupObject[ProductGroup::GROUP_COUNT_REPAIR] = $productGroupRepairData[ProductGroup::GROUP_COUNT_REPAIR];
                        break;
                    }
                }
                unset($productGroupObject);
            }
            $dbStatement->close();
        } catch(Exception $e) {
            # product group overview query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        # execute product group overview - rent query
        try {
            $dbQuery = "SELECT `product_group`, COUNT(`product_index`) FROM `Products` WHERE `product_rent` != 0 GROUP BY `product_group`";
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # product group overview query error
                printOutput(-4, "PRODUCT GROUP LIST FAILURE : ".$dbStatement->error);
                exit();
            }
            $productGroupRentData = array();
            $dbStatement->bind_result($productGroupRentData[ProductGroup::GROUP_INDEX], $productGroupRentData[ProductGroup::GROUP_COUNT_RENT]);
            while($dbStatement->fetch()) {
                foreach ($productGroupJsonResult as &$productGroupObject) {
                    if ($productGroupObject[ProductGroup::GROUP_INDEX] == $productGroupRentData[ProductGroup::GROUP_INDEX]) {
                        $productGroupObject[ProductGroup::GROUP_COUNT_RENT] = $productGroupRentData[ProductGroup::GROUP_COUNT_RENT];
                        break;
                    }
                }
                unset($productGroupObject);
            }
            $dbStatement->close();
        } catch(Exception $e) {
            # product group overview query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        # change products overview empty values to default
        foreach ($productGroupJsonResult as &$productGroupJsonObject) {
            if (!isset($productGroupJsonObject[ProductGroup::GROUP_COUNT_AVAILABLE])) {
                $productGroupJsonObject[ProductGroup::GROUP_COUNT_AVAILABLE] = 0;
            }
            if (!isset($productGroupJsonObject[ProductGroup::GROUP_COUNT_UNAVAILABLE])) {
                $productGroupJsonObject[ProductGroup::GROUP_COUNT_UNAVAILABLE] = 0;
            }
            if (!isset($productGroupJsonObject[ProductGroup::GROUP_COUNT_BROKEN])) {
                $productGroupJsonObject[ProductGroup::GROUP_COUNT_BROKEN] = 0;
            }
            if (!isset($productGroupJsonObject[ProductGroup::GROUP_COUNT_REPAIR])) {
                $productGroupJsonObject[ProductGroup::GROUP_COUNT_REPAIR] = 0;
            }
            if (!isset($productGroupJsonObject[ProductGroup::GROUP_COUNT_RENT])) {
                $productGroupJsonObject[ProductGroup::GROUP_COUNT_RENT] = 0;
            }
        }
        unset($productGroupJsonObject);

        return $productGroupJsonResult;
    }

    public function getProductGroups($params = null) {
        # prepare product group list result
        $productGroupJsonResult = array();

        # execute product group list query
        try {
            $dbQuery = "SELECT `product_group_index`, `product_group_name`, `product_group_rentable`, `product_group_priority` FROM `ProductGroup`";
            $db = DatabaseManager::getInstance()->getConnection();
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # product group list query error
                printOutput(-4, "PRODUCT GROUP LIST FAILURE : ".$dbStatement->error);
                exit();
            }
            $productGroupData = array();
            $dbStatement->bind_result($productGroupData[ProductGroup::GROUP_INDEX], $productGroupData[ProductGroup::GROUP_NAME], $productGroupData[ProductGroup::GROUP_RENTABLE], $productGroupData[ProductGroup::GROUP_PRIORITY]);
            while($dbStatement->fetch()) {
                $productGroupJsonObject = array();
                $productGroupJsonObject[ProductGroup::GROUP_INDEX] = $productGroupData[ProductGroup::GROUP_INDEX];
                $productGroupJsonObject[ProductGroup::GROUP_NAME] = $productGroupData[ProductGroup::GROUP_NAME];
                $productGroupJsonObject[ProductGroup::GROUP_RENTABLE] = $productGroupData[ProductGroup::GROUP_RENTABLE];
                $productGroupJsonObject[ProductGroup::GROUP_PRIORITY] = $productGroupData[ProductGroup::GROUP_PRIORITY];
                array_push($productGroupJsonResult, $productGroupJsonObject);
            }
            $dbStatement->close();
        } catch(Exception $e) {
            # product group list query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        return $productGroupJsonResult;
    }

    public function addProductGroup($params, $validation = null) {
        # execute product group creation query
        try {
            $dbQuery = "INSERT INTO `ProductGroup` (`product_group_name`, `product_group_rentable`, `product_group_priority`) VALUES (?, ?, ?)";
            $db = DatabaseManager::getInstance()->getConnection();
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->bind_param("sii", $params[ProductGroup::PRODUCT_GROUP_NAME], $params[ProductGroup::PRODUCT_GROUP_RENTABLE], $params[ProductGroup::PRODUCT_GROUP_PRIORITY]);
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # product group creation query error
                printOutput(-4, "ADD PRODUCT GROUP FAILURE : ".$dbStatement->error);
                exit();
            }
            $productGroupIndex = $dbStatement->insert_id;
            if (isset($validation)) {
                LogManager::getInstance()->addLog([LOG::LOG_USER => $validation[User::USER_INDEX], LOG::LOG_TYPE => LogType::TYPE_PRODUCT_GROUP_ADD]);
            }
            $dbStatement->close();
        } catch(Exception $e) {
            # product group creation query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        return $productGroupIndex;
    }

    public function deleteProductGroup($productGroupIndex, $validation = null) {
        # execute product group deletion query
        try {
            $dbQuery = "DELETE FROM `ProductGroup` WHERE `product_group_index` = ?";
            $db = DatabaseManager::getInstance()->getConnection();
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->bind_param("i", $productGroupIndex);
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # product group deletion query error
                printOutput(-4, "DELETE PRODUCT GROUP FAILURE : ".$dbStatement->error);
                exit();
            }
            if (isset($validation)) {
                LogManager::getInstance()->addLog([LOG::LOG_USER => $validation[User::USER_INDEX], LOG::LOG_TYPE => LogType::TYPE_PRODUCT_GROUP_DELETE]);
            }
            $dbStatement->close();
        } catch(Exception $e) {
            # product group deletion query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }
    }

    private function executeModifyProductGroup(int $groupIndex, $modifyQuery, string $bindType, $bindValue, $validation = null) {
        # execute product group modification query
        try {
            $db = DatabaseManager::getInstance()->getConnection();
            $dbStatement = $db->prepare($modifyQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->bind_param($bindType, $bindValue, $groupIndex);
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # product group modification query error
                printOutput(-4, "MODIFY PRODUCT GROUP FAILURE : ".$dbStatement->error);
                exit();
            }
            $dbStatement->close();
        } catch(Exception $e) {
            # product group modification query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }
    }

    public function modifyProductGroup($params, $validation = null) {
        if (!isset($params[ProductGroup::PRODUCT_GROUP_INDEX])) {
            return;
        }
        if (isset($params[ProductGroup::PRODUCT_GROUP_NAME])) {
            $nameModifyQuery = "UPDATE `ProductGroup` SET `product_group_name` = ? WHERE `product_group_index` = ?";
            self::executeModifyProductGroup($params[ProductGroup::PRODUCT_GROUP_INDEX], $nameModifyQuery, "si", $params[ProductGroup::PRODUCT_GROUP_NAME]);
        }
        if (isset($params[ProductGroup::PRODUCT_GROUP_RENTABLE])) {
            $rentableModifyQuery = "UPDATE `ProductGroup` SET `product_group_rentable` = ? WHERE `product_group_index` = ?";
            self::executeModifyProductGroup($params[ProductGroup::PRODUCT_GROUP_INDEX], $rentableModifyQuery, "ii", $params[ProductGroup::PRODUCT_GROUP_RENTABLE]);
        }
        if (isset($params[ProductGroup::PRODUCT_GROUP_PRIORITY])) {
            $priorityModifyQuery = "UPDATE `ProductGroup` SET `product_group_priority` = ? WHERE `product_group_index` = ?";
            self::executeModifyProductGroup($params[ProductGroup::PRODUCT_GROUP_INDEX], $priorityModifyQuery, "ii", $params[ProductGroup::PRODUCT_GROUP_PRIORITY]);
        }
        if (isset($validation)) {
            LogManager::getInstance()->addLog([LOG::LOG_USER => $validation[User::USER_INDEX], LOG::LOG_TYPE => LogType::TYPE_PRODUCT_GROUP_MODIFY]);
        }
    }

}

?>