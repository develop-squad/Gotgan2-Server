<?php

class LogManager extends Singleton {

    protected static $instance;

    public function getLogs() : array {

    }

    public function getLogsByType($logTypeIndex) : array {

    }

    public function getLogsByProduct($productIndex) : array {

    }

    public function getLogsByUser($userIndex) : array {

    }

    public function getLogByIndex($logIndex) {

    }

    public function addLog($params) {
        if (!isset($logType) || !isset($logProduct) || !isset($logUser)) return -1;
        # execute log query
        try {
            $DB_SQL_LOG = "INSERT INTO `Logs` (`log_product`, `log_user`, `log_type`, `log_text`) VALUES (?, ?, ?, ?)";
            // TODO databaseManager getConnection
            $DB = "";
            $DB_STMT_LOG = $DB->prepare($DB_SQL_LOG);
            # database query not ready
            if (!$DB_STMT_LOG) {
                $output = array();
                $output["result"] = -2;
                $output["error"] = "DB QUERY FAILURE : " . $DB->error;
                $outputJson = json_encode($output);
                echo urldecode($outputJson);
                exit();
            }
            $logText = "";
            $DB_STMT_LOG->bind_param("iiis", $logProduct, $logUser, $logType, $logText);
            $DB_STMT_LOG->execute();
            if ($DB_STMT_LOG->errno != 0) {
                # log query error
                $output = array();
                $output["result"] = -2;
                $output["error"] = "DB QUERY FAILURE : ".$DB_STMT_LOG->error;
                $outputJson = json_encode($output);
                echo urldecode($outputJson);
                exit();
            }
            $TEMP_LOG_INSERTED = $DB_STMT_LOG->insert_id;
            $DB_STMT_LOG->close();
        } catch (Exception $e) {
            # log query error
            $output = array();
            $output["result"] = -2;
            $output["error"] = "DB QUERY FAILURE : ".$DB->error;
            $outputJson = json_encode($output);
            echo urldecode($outputJson);
            exit();
        }
        return $TEMP_LOG_INSERTED;
    }

    public function deleteLog($logIndex) {

    }

    public function printLogType() {

    }

}

?>