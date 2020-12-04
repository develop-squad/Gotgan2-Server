<?php

class RentManager extends Singleton {

    protected static $instance;

    public function getRents($params) : array {
        # prepare rent list result
        $rentJsonResult = array();

        # execute rent list query
        try {
            $dbQuery = "
                SELECT `R`.`rent_index`,
                    `R`.`rent_user` as `rent_user_index`,
                    `U`.`user_name` as `rent_user_name`,
                    `U`.`user_id` as `rent_user_id`,
                    `R`.`rent_product` as `rent_product_index`,
                    `P`.`product_group` as `rent_product_group_index`,
                    `PG`.`product_group_name` as `rent_product_group_name`,
                    `P`.`product_name` as `rent_product_name`,
                    `P`.`product_barcode` as `rent_product_barcode`,
                    `R`.`rent_status`, `R`.`rent_time_start`,
                    `R`.`rent_time_end`, `R`.`rent_time_return`
                FROM `Rents` AS `R`
                    LEFT OUTER JOIN `Users` AS `U` ON (`R`.`rent_user` = `U`.`user_index`) 
                    LEFT OUTER JOIN `Products` AS `P` ON (`R`.`rent_product` = `P`.`product_index`) 
                    LEFT OUTER JOIN `ProductGroup` AS `PG` ON (`P`.`product_group` = `PG`.`product_group_index`) 
                WHERE 1=1";
            if (isset($params[Rent::RENT_INDEX])) {
                $dbQuery .= " AND `rent_index` = ".$params[Rent::RENT_INDEX];
            }
            if (isset($params[Rent::RENT_USER])) {
                $dbQuery .= " AND `rent_user` = ".$params[Rent::RENT_USER];
            }
            if (isset($params[Rent::RENT_PRODUCT])) {
                $dbQuery .= " AND `rent_product` = ".$params[Rent::RENT_PRODUCT];
            }
            if (isset($params[Product::PRODUCT_BARCODE])) {
                $dbQuery .= " AND `product_barcode` = ".$params[Product::PRODUCT_BARCODE];
            }
            if (isset($params[Rent::RENT_STATUS])) {
                $dbQuery .= " AND `rent_status` = ".$params[Rent::RENT_STATUS];
            }
            if (isset($params[Rent::RENT_DELAYED]) && $params[Rent::RENT_DELAYED] == 1) {
                $dbQuery .= " AND (`rent_time_end` IS NOT NULL AND `rent_time_return` IS NOT NULL AND `rent_time_return` > `rent_time_end`)";
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
                # rent list query error
                printOutput(-4, "RENT LIST FAILURE : ".$dbStatement->error);
                exit();
            }
            $rentData = array();
            $dbStatement->bind_result($rentData[Rent::RENT_INDEX], $rentData[Rent::RENT_USER], $rentData[User::USER_NAME], $rentData[User::USER_ID], $rentData[Rent::RENT_PRODUCT], $rentData[Product::PRODUCT_GROUP],
                $rentData[ProductGroup::PRODUCT_GROUP_NAME], $rentData[Product::PRODUCT_NAME], $rentData[Product::PRODUCT_BARCODE], $rentData[Rent::RENT_STATUS], $rentData[Rent::RENT_TIME_START], $rentData[Rent::RENT_TIME_END], $rentData[Rent::RENT_TIME_RETURN]);
            while($dbStatement->fetch()) {
                $rentJsonObject = array();
                $rentJsonObject[Rent::RENT_INDEX] = $rentData[Rent::RENT_INDEX];
                $rentJsonObject[Rent::RENT_USER_INDEX] = $rentData[Rent::RENT_USER];
                $rentJsonObject[Rent::RENT_USER_NAME] = $rentData[User::USER_NAME];
                $rentJsonObject[Rent::RENT_USER_ID] = $rentData[User::USER_ID];
                $rentJsonObject[Rent::RENT_PRODUCT_INDEX] = $rentData[Rent::RENT_PRODUCT];
                $rentJsonObject[Rent::RENT_PRODUCT_GROUP_INDEX] = $rentData[Product::PRODUCT_GROUP];
                if (isset($rentData[ProductGroup::PRODUCT_GROUP_NAME])) {
                    $rentJsonObject[Rent::RENT_PRODUCT_GROUP_NAME] = $rentData[ProductGroup::PRODUCT_GROUP_NAME];
                } else {
                    $rentJsonObject[Rent::RENT_PRODUCT_GROUP_NAME] = "";
                }
                $rentJsonObject[Rent::RENT_PRODUCT_NAME] = $rentData[Product::PRODUCT_NAME];
                if (isset($rentData[Product::PRODUCT_BARCODE])) {
                    $rentJsonObject[Rent::RENT_PRODUCT_BARCODE] = $rentData[Product::PRODUCT_BARCODE];
                }
                $rentJsonObject[Rent::RENT_STATUS] = $rentData[Rent::RENT_STATUS];
                $rentJsonObject[Rent::RENT_TIME_START] = $rentData[Rent::RENT_TIME_START];
                $rentJsonObject[Rent::RENT_TIME_END] = $rentData[Rent::RENT_TIME_END];
                $rentJsonObject[Rent::RENT_TIME_RETURN] = $rentData[Rent::RENT_TIME_RETURN];
                array_push($rentJsonResult, $rentJsonObject);
            }
            $dbStatement->close();
        } catch(Exception $e) {
            # rent list query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        return $rentJsonResult;
    }

    public function getRentRequest($params) {
        # get rent details
        try {
            $dbQuery = "SELECT `PG`.`product_group_rentable`, `R`.rent_index, `R`.`rent_product`, `R`.`rent_user`, `R`.`rent_status` FROM `Rents` as `R` LEFT OUTER JOIN `Products` AS `P` ON (`R`.`rent_product` = `P`.`product_index`) LEFT OUTER JOIN `ProductGroup` AS `PG` ON (`P`.`product_group` = `PG`.`product_group_index`) WHERE 1 = 1";
            if (isset($params[Rent::RENT_INDEX])) {
                $dbQuery .= " AND `R`.`rent_index` = ?";
            }
            if (isset($params[Product::PRODUCT_BARCODE])) {
                $dbQuery .= " AND `P`.`product_barcode` = ?";
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
                # rent detail query error
                printOutput(-4, "RENT PRODUCT SEARCH FAILURE : ".$dbStatement->error);
                exit();
            }
            $rentRequestData = array();
            $dbStatement->bind_result($rentRequestData[ProductGroup::PRODUCT_GROUP_RENTABLE], $rentRequestData[Rent::RENT_INDEX], $rentRequestData[Rent::RENT_PRODUCT], $rentRequestData[Rent::RENT_USER], $rentRequestData[Rent::RENT_STATUS]);
            $dbStatement->fetch();
            $dbStatement->close();
        } catch(Exception $e) {
            # rent detail query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        return $rentRequestData;
    }

    public function addRent($params, $validation = null) {
        # execute rent creation query
        try {
            $dbQuery = "INSERT INTO `Rents` (`rent_user`, `rent_product`, `rent_status`, `rent_time_start`) VALUES (?, ?, 1, ''".$params[Rent::RENT_TIME_START]."')";
            $db = DatabaseManager::getInstance()->getConnection();
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->bind_param("ii", $params[Rent::RENT_USER], $params[Rent::RENT_PRODUCT]);
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # rent creation query error
                printOutput(-4, "ADD RENT FAILURE : ".$dbStatement->error);
                exit();
            }
            $rentIndex = $dbStatement->insert_id;
            $dbStatement->close();
        } catch(Exception $e) {
            # rent creation query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        # update product status
        try {
            $dbQuery = "UPDATE `Products` SET `product_rent` = ? WHERE `product_index` = ?";
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : " . $db->error);
                exit();
            }
            $dbStatement->bind_param("ii", $rentIndex, $params[Rent::RENT_PRODUCT]);
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # update product query error
                printOutput(-2, "UPDATE PRODUCT STATUS FAILURE : " . $dbStatement->error);
                exit();
            }
            $dbStatement->close();
        } catch (Exception $e) {
            # update product query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        # rent creation log
        LogManager::getInstance()->addLog([LOG::LOG_PRODUCT => $params[Rent::RENT_PRODUCT], LOG::LOG_USER => $validation[User::USER_INDEX], LOG::LOG_TYPE => LogType::TYPE_RENT_ADD]);

        return $rentIndex;
    }

    public function allowRent($params, $validation = null) {
        # execute rent allow query
        try {
            $dbQuery = "UPDATE `Rents` SET `rent_status` = 2, `rent_time_start` = NOW(), `rent_time_end` = DATE_ADD(NOW(), INTERVAL ? DAY) WHERE `rent_index` = ?";
            $db = DatabaseManager::getInstance()->getConnection();
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->bind_param("ii", $params[ProductGroup::PRODUCT_GROUP_RENTABLE], $params[Rent::RENT_INDEX]);
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # rent allow query error
                printOutput(-4, "UPDATE RENT FAILURE : ".$dbStatement->error);
                exit();
            }
            $dbStatement->close();
        } catch(Exception $e) {
            # rent allow query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        # execute rent allow query
        try {
            $dbQuery = "UPDATE `Products` SET `product_rent` = ? WHERE `product_index` = ?";
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->bind_param("ii", $params[Rent::RENT_INDEX], $params[Rent::RENT_PRODUCT]);
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # rent allow query error
                printOutput(-4, "UPDATE RENT FAILURE : ".$dbStatement->error);
                exit();
            }
            $dbStatement->close();
        } catch(Exception $e) {
            # rent allow query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        # rent allow log
        LogManager::getInstance()->addLog([LOG::LOG_PRODUCT => $params[Rent::RENT_PRODUCT], LOG::LOG_USER => $params[Rent::RENT_USER], LOG::LOG_TYPE => LogType::TYPE_RENT_ALLOW]);

    }

    public function deleteRent($params, $validation = null) {
        # execute rent deletion query
        try {
            $dbQuery = "DELETE FROM `Rents` WHERE `rent_index` = ?";
            $db = DatabaseManager::getInstance()->getConnection();
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->bind_param("i", $params[Rent::RENT_INDEX]);
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # rent deletion query error
                printOutput(-4, "UPDATE RENT FAILURE : ".$dbStatement->error);
                exit();
            }
            $dbStatement->close();
        } catch(Exception $e) {
            # rent deletion query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        # execute rent deletion query
        try {
            $dbQuery = "UPDATE `Products` SET `product_rent` = 0 WHERE `product_index` = ?";
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->bind_param("i", $params[Rent::RENT_PRODUCT]);
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # rent deletion query error
                printOutput(-4, "UPDATE RENT FAILURE : ".$dbStatement->error);
                exit();
            }
            $dbStatement->close();
        } catch(Exception $e) {
            # rent deletion query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        # rent deletion log
        LogManager::getInstance()->addLog([LOG::LOG_PRODUCT => $params[Rent::RENT_PRODUCT], LOG::LOG_USER => $params[Rent::RENT_USER], LOG::LOG_TYPE => LogType::TYPE_RENT_DELETE]);

    }

    public function returnRent($params, $validation = null) {
        # execute rent return query
        try {
            $dbQuery = "UPDATE `Rents` SET `rent_status` = 0, `rent_time_return` = NOW() WHERE `rent_index` = ?";
            $db = DatabaseManager::getInstance()->getConnection();
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->bind_param("i", $params[Rent::RENT_INDEX]);
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # rent return query error
                printOutput(-4, "UPDATE RENT FAILURE : ".$dbStatement->error);
                exit();
            }
            $dbStatement->close();
        } catch(Exception $e) {
            # rent return query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        # execute rent return query
        try {
            $dbQuery = "UPDATE `Products` SET `product_rent` = 0 WHERE `product_index` = ?";
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->bind_param("i", $params[Rent::RENT_PRODUCT]);
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # rent return query error
                printOutput(-4, "UPDATE RENT FAILURE : ".$dbStatement->error);
                exit();
            }
            $dbStatement->close();
        } catch(Exception $e) {
            # rent return query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        # rent return log
        LogManager::getInstance()->addLog([LOG::LOG_PRODUCT => $params[Rent::RENT_PRODUCT], LOG::LOG_USER => $params[Rent::RENT_USER], LOG::LOG_TYPE => LogType::TYPE_RENT_RETURN]);

    }

    private function checkRents($isLate) {
        # prepare rent due list result
        $rentCheckJsonResult = array();

        # execute rent due list query
        try {
            $dbQuery = "
                SELECT `R`.`rent_index`,
                    `R`.`rent_user` as `rent_user_index`,
                    `U`.`user_name` as `rent_user_name`,
                    `U`.`user_id` as `rent_user_id`,
                    `U`.`user_email` as `rent_user_email`,
                    `U`.`user_uuid` as `rent_user_uuid`,
                    `R`.`rent_product` as `rent_product_index`,
                    `P`.`product_group` as `rent_product_group_index`,
                    `PG`.`product_group_name` as `rent_product_group_name`,
                    `P`.`product_name` as `rent_product_name`,
                    `P`.`product_barcode` as `rent_product_barcode`,
                    `R`.`rent_status`, `R`.`rent_time_start`,
                    `R`.`rent_time_end`, `R`.`rent_time_return`
                FROM `Rents` AS `R`
                    LEFT OUTER JOIN `Users` AS `U` ON (`R`.`rent_user` = `U`.`user_index`) 
                    LEFT OUTER JOIN `Products` AS `P` ON (`R`.`rent_product` = `P`.`product_index`) 
                    LEFT OUTER JOIN `ProductGroup` AS `PG` ON (`P`.`product_group` = `PG`.`product_group_index`) 
                WHERE `R`.`rent_status` = 2";
            if ($isLate) {
                $dbQuery .= "AND CAST(`R`.`rent_time_end` AS DATE) < CAST(NOW() AS DATE)";
            } else {
                $dbQuery .= "AND CAST(`R`.`rent_time_end` AS DATE) = CAST(NOW() AS DATE)";
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
                # rent due list query error
                printOutput(-4, "RENT DUE LIST FAILURE : ".$dbStatement->error);
                exit();
            }
            $rentCheckData = array();
            $dbStatement->bind_result($rentCheckData[Rent::RENT_INDEX], $rentCheckData[Rent::RENT_USER], $rentCheckData[User::USER_NAME], $rentCheckData[User::USER_ID], $rentCheckData[User::USER_EMAIL], $rentCheckData[User::USER_UUID], $rentCheckData[Rent::RENT_PRODUCT], $rentCheckData[Product::PRODUCT_GROUP],
                $rentCheckData[ProductGroup::PRODUCT_GROUP_NAME], $rentCheckData[Product::PRODUCT_NAME], $rentCheckData[Product::PRODUCT_BARCODE], $rentCheckData[Rent::RENT_STATUS], $rentCheckData[Rent::RENT_TIME_START], $rentCheckData[Rent::RENT_TIME_END], $rentCheckData[Rent::RENT_TIME_RETURN]);
            while($dbStatement->fetch()) {
                $rentCheckJsonObject = array();
                $rentCheckJsonObject[Rent::RENT_INDEX] = $rentCheckData[Rent::RENT_INDEX];
                $rentCheckJsonObject[Rent::RENT_USER_INDEX] = $rentCheckData[Rent::RENT_USER];
                $rentCheckJsonObject[Rent::RENT_USER_NAME] = $rentCheckData[User::USER_NAME];
                $rentCheckJsonObject[Rent::RENT_USER_ID] = $rentCheckData[User::USER_ID];
                if (isset($rentCheckData[User::USER_EMAIL])) {
                    $rentCheckJsonObject[Rent::RENT_USER_EMAIL] = $rentCheckData[User::USER_EMAIL];
                }
                if (isset($rentCheckData[User::USER_UUID])) {
                    $rentCheckJsonObject[Rent::RENT_USER_UUID] = $rentCheckData[User::USER_UUID];
                }
                $rentCheckJsonObject[Rent::RENT_PRODUCT_INDEX] = $rentCheckData[Rent::RENT_PRODUCT];
                $rentCheckJsonObject[Rent::RENT_PRODUCT_GROUP_INDEX] = $rentCheckData[Product::PRODUCT_GROUP];
                if (isset($rentCheckData[ProductGroup::PRODUCT_GROUP_NAME])) {
                    $rentCheckJsonObject[Rent::RENT_PRODUCT_GROUP_NAME] = $rentCheckData[ProductGroup::PRODUCT_GROUP_NAME];
                } else {
                    $rentCheckJsonObject[Rent::RENT_PRODUCT_GROUP_NAME] = "";
                }
                $rentCheckJsonObject[Rent::RENT_PRODUCT_NAME] = $rentCheckData[Product::PRODUCT_NAME];
                if (isset($rentCheckData[Product::PRODUCT_BARCODE])) {
                    $rentCheckJsonObject[Rent::RENT_PRODUCT_BARCODE] = $rentCheckData[Product::PRODUCT_BARCODE];
                }
                $rentCheckJsonObject[Rent::RENT_STATUS] = $rentCheckData[Rent::RENT_STATUS];
                $rentCheckJsonObject[Rent::RENT_TIME_START] = $rentCheckData[Rent::RENT_TIME_START];
                $rentCheckJsonObject[Rent::RENT_TIME_END] = $rentCheckData[Rent::RENT_TIME_END];
                $rentCheckJsonObject[Rent::RENT_TIME_RETURN] = $rentCheckData[Rent::RENT_TIME_RETURN];
                array_push($rentCheckJsonResult, $rentCheckJsonObject);
            }
            $dbStatement->close();
        } catch(Exception $e) {
            # rent due list query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        return $rentCheckJsonResult;
    }

    public function checkRentsDue() {
        return self::checkRents(false);
    }

    public function checkRentsLate() {
        return self::checkRents(true);
    }

}

?>