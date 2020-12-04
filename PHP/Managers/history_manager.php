<?php

class HistoryManager extends Singleton {

    protected static $instance;

    /**
     * @return array
     */
    public function getHistories() {
        $historyJsonResult = array();
        # execute history list query
        try {
            $dbQuery = "SELECT `".History::HISTORY_INDEX."`, `".History::HISTORY_TIME."`, `".History::HISTORY_USER_TOTAL."`, `".History::HISTORY_PRODUCT_TOTAL."`, `".History::HISTORY_PRODUCT_AVAILABLE."`, `".History::HISTORY_PRODUCT_RENT."`, `".History::HISTORY_RENT_TOTAL."l` FROM `".History::HISTORY."` ORDER BY `".History::HISTORY_TIME."` DESC LIMIT 30";
            $db = DatabaseManager::getInstance()->getConnection();
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # history list query error
                printOutput(-4, "HISTORY LIST FAILURE : ".$dbStatement->error);
                exit();
            }
            $historyIndex = 0;
            $historyTime = "";
            $historyUserTotal = 0;
            $historyProductTotal = 0;
            $historyProductAvailable = 0;
            $historyProductRent = 0;
            $historyRentTotal = 0;
            $dbStatement->bind_result($historyIndex, $historyTime, $historyUserTotal, $historyProductTotal, $historyProductAvailable, $historyProductRent, $historyRentTotal);
            while($dbStatement->fetch()) {
                $historyJsonObject = array();
                $historyJsonObject[History::HISTORY_INDEX] = $historyIndex;
                $historyJsonObject[History::HISTORY_TIME] = $historyTime;
                $historyJsonObject[History::HISTORY_USER_TOTAL] = $historyUserTotal;
                $historyJsonObject[History::HISTORY_PRODUCT_TOTAL] = $historyProductTotal;
                $historyJsonObject[History::HISTORY_PRODUCT_AVAILABLE] = $historyProductAvailable;
                $historyJsonObject[History::HISTORY_PRODUCT_RENT] = $historyProductRent;
                $historyJsonObject[History::HISTORY_RENT_TOTAL] = $historyRentTotal;
                array_push($historyJsonResult, $historyJsonObject);
            }
            $dbStatement->close();
        } catch(Exception $e) {
            # history list query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        return $historyJsonResult;
    }

    public function checkHistories() {
        # execute rent count query
        try {
            $dbQuery = "SELECT COUNT(*) FROM `".Rent::RENT."` WHERE `".Rent::RENT_STATUS."` != 1";
            $db = DatabaseManager::getInstance()->getConnection();
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # rent count query error
                printOutput(-4, "RENT COUNT FAILURE : ".$dbStatement->error);
                exit();
            }
            $rentCount = 0;
            $dbStatement->bind_result($rentCount);
            $dbStatement->fetch();
            $dbStatement->close();
        } catch(Exception $e) {
            # rent count query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        # execute user count query
        try {
            $dbQuery = "SELECT COUNT(*) FROM `".User::USER."`";
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # user count query error
                printOutput(-4, "USER COUNT FAILURE : ".$dbStatement->error);
                exit();
            }
            $userCount = 0;
            $dbStatement->bind_result($userCount);
            $dbStatement->fetch();
            $dbStatement->close();
        } catch(Exception $e) {
            # user count query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        # execute product total count query
        try {
            $dbQuery = "SELECT COUNT(*) FROM `".Product::PRODUCT."`";
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # product total count query error
                printOutput(-4, "PRODUCT TOTAL COUNT FAILURE : ".$dbStatement->error);
                exit();
            }
            $productTotalCount = 0;
            $dbStatement->bind_result($productTotalCount);
            $dbStatement->fetch();
            $dbStatement->close();
        } catch(Exception $e) {
            # product total count query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        # execute product available count query
        try {
            $dbQuery = "SELECT COUNT(*) FROM `".Product::PRODUCT."` WHERE `".Product::PRODUCT_STATUS."` = 0";
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # product available count query error
                printOutput(-4, "PRODUCT AVAILABLE COUNT FAILURE : ".$dbStatement->error);
                exit();
            }
            $productAvailableCount = 0;
            $dbStatement->bind_result($productAvailableCount);
            $dbStatement->fetch();
            $dbStatement->close();
        } catch(Exception $e) {
            # product available count query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        # execute product rent count query
        try {
            $dbQuery = "SELECT COUNT(*) FROM `".Product::PRODUCT."` WHERE `".Product::PRODUCT_RENT."` != 0";
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # product rent count query error
                printOutput(-4, "PRODUCT RENT COUNT FAILURE : ".$dbStatement->error);
                exit();
            }
            $productRentCount = 0;
            $dbStatement->bind_result($productRentCount);
            $dbStatement->fetch();
            $dbStatement->close();
        } catch(Exception $e) {
            # product rent count query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        # execute insert history query
        try {
            $dbQuery = "INSERT INTO `".History::HISTORY."` (`".History::HISTORY_TIME."`, `".History::HISTORY_USER_TOTAL."`, `".History::HISTORY_PRODUCT_TOTAL."`, `".History::HISTORY_PRODUCT_AVAILABLE."`, `".History::HISTORY_PRODUCT_RENT."`, `".History::HISTORY_RENT_TOTAL."`) VALUES (CAST(NOW() AS DATE), ?, ?, ?, ?, ?)";
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->bind_param("iiiii", $userCount, $productTotalCount, $productAvailableCount, $productRentCount, $rentCount);
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                if ($dbStatement->errno == 1062) {
                    # already today history is in database
                    printOutput(-3, "ALREADY TODAY HISTORY IS IN DATABASE");
                    exit();
                } else {
                    # insert history query error
                    printOutput(-4, "INSERT HISTORY FAILURE : ".$dbStatement->error);
                    exit();
                }
            }
            $dbStatement->close();
        } catch(Exception $e) {
            # insert history query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }
    }

}

?>