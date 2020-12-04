<?php

class LogManager extends Singleton {

    protected static $instance;

    public function getLogs($params) : array {
        # prepare log list result
        $logJsonResult = array();

        # execute log list query
        try {
            $dbQuery = "
    SELECT `L`.`log_index`,
       `L`.`log_product` as `log_product_index`,
       `P`.`product_name` as `log_product_name`, 
       `L`.`log_user` as `log_user_index`, 
       `U`.`user_name` as `log_user_name`, 
       `U`.`user_id` as `log_user_id`, 
       `L`.`log_type`, `L`.`log_text`, `L`.`log_time` 
    FROM `Logs` AS `L` 
        LEFT OUTER JOIN `Users` AS `U` ON (`L`.`log_user` = `U`.`user_index`) 
        LEFT OUTER JOIN `Products` AS `P` ON (`L`.`log_product` = `P`.`product_index`) 
    WHERE 1=1";
            if (isset($params[LOG::LOG_INDEX])) {
                $dbQuery .= " AND `log_index` = ".$params[LOG::LOG_INDEX];
            }
            if (isset($params[LOG::LOG_PRODUCT])) {
                $dbQuery .= " AND `log_product` = ".$params[LOG::LOG_PRODUCT];
            }
            if (isset($params[LOG::LOG_USER])) {
                $dbQuery .= " AND `log_user` = ".$params[LOG::LOG_USER];
            }
            if (isset($log_type)) {
                $dbQuery .= " AND `log_type` = ".$params[LOG::LOG_TYPE];
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
                # log list query error
                printOutput(-4, "LOG LIST FAILURE : ".$dbStatement->error);
                exit();
            }
            $logIndex = -1;
            $logProductIndex = -1;
            $logProductName = "";
            $logUserIndex = -1;
            $logUserName = "";
            $logUserID = "";
            $logType = -1;
            $logText = "";
            $logTime = "";
            $dbStatement->bind_result($logIndex, $logProductIndex, $logProductName, $logUserIndex, $logUserName, $logUserID, $logType, $logText, $logTime);
            while($dbStatement->fetch()) {
                $logJsonObject = array();
                $logJsonObject["log_index"] = $logIndex;
                $logJsonObject["log_product_index"] = $logProductIndex;
                if (isset($logProductName)) {
                    $logJsonObject["log_product_name"] = $logProductName;
                }
                $logJsonObject["log_user_index"] = $logUserIndex;
                if (isset($logUserName)) {
                    $logJsonObject["log_user_name"] = $logUserName;
                }
                if (isset($logUserID)) {
                    $logJsonObject["log_user_id"] = $logUserID;
                }
                $logJsonObject["log_type"] = $logType;
                if (isset($logText)) {
                    $logJsonObject["log_text"] = $logText;
                }
                $logJsonObject["log_time"] = $logTime;
                array_push($logJsonResult, $logJsonObject);
            }
            $dbStatement->close();
        } catch(Exception $e) {
            # log list query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        return $logJsonResult;
    }

    public function addLog($params) : int {
        if (!isset($params[Log::LOG_PRODUCT]) || !isset($params[Log::LOG_USER]) || !isset($params[Log::LOG_TYPE])) return -1;
        # execute log query
        try {
            $dbQuery = "INSERT INTO `Logs` (`log_product`, `log_user`, `log_type`, `log_text`) VALUES (?, ?, ?, ?)";
            $db = DatabaseManager::getInstance()->getConnection();
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : " . $db->error);
                exit();
            }
            $dbStatement->bind_param("iiis", $params[Log::LOG_PRODUCT], $params[Log::LOG_USER], $params[Log::LOG_TYPE], $params[Log::LOG_TEXT]);
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # log query error
                printOutput(-2, "DB QUERY FAILURE : ".$dbStatement->error);
                exit();
            }
            $logIndex = $dbStatement->insert_id;
            $dbStatement->close();
        } catch (Exception $e) {
            # log query error
            printOutput(-2, "DB QUERY FAILURE : ".$dbStatement->error);
            exit();
        }
        return $logIndex;
    }

    public function deleteLog($logIndex) {
        # execute log deletion query
        try {
            $dbQuery = "DELETE FROM `Logs` WHERE `log_index` = ?";
            $db = DatabaseManager::getInstance()->getConnection();
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->bind_param("i", $logIndex);
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # log deletion query error
                printOutput(-4, "DELETE LOG FAILURE : ".$dbStatement->error);
                exit();
            }
            $dbStatement->close();
        } catch(Exception $e) {
            # log deletion query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }
    }

    public function getLogTypes() {
        # prepare log type list result
        $logTypeJsonResult = array();

        # execute log type list query
        try {
            $dbQuery = "SELECT `log_type_index`, `log_type_name`, `log_type_level` FROM `LogType`";
            $db = DatabaseManager::getInstance()->getConnection();
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # log type list query error
                printOutput(-4, "LOG TYPE LIST FAILURE : ".$dbStatement->error);
                exit();
            }
            $logTypeIndex = -1;
            $logTypeName = "";
            $logTypeLevel = -1;
            $dbStatement->bind_result($logTypeIndex, $logTypeName, $logTypeLevel);
            while($dbStatement->fetch()) {
                $logTypeJsonObject = array();
                $logTypeJsonObject["type_index"] = $logTypeIndex;
                $logTypeJsonObject["type_name"] = $logTypeName;
                $logTypeJsonObject["type_level"] = $logTypeLevel;
                array_push($logTypeJsonResult, $logTypeJsonObject);
            }
            $dbStatement->close();
        } catch(Exception $e) {
            # log type list query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        return $logTypeJsonResult;
    }

}

?>