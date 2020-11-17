<?php

class ServiceManager extends Singleton {

    protected static $instance;

    public function loginAccount($params) {

    }

    public function loginSession(string $session) {
        $_validation = array();
        $_validation["user_index"] = -1;
        $_validation["user_level"] = -1;
        $_validation["user_id"] = "";
        $_validation["user_name"] = "";

        # execute session validate query
        try {
            $DB_SQL_SESSION = "SELECT `user_session_user` FROM `UserSession` WHERE `user_session_key` = ?";
            // TODO : databaseManager getConnection
            $DB = "";
            $DB_STMT_SESSION = $DB->prepare($DB_SQL_SESSION);
            # database query not ready
            if (!$DB_STMT_SESSION) {
                printOutput(-2, "DB QUERY FAILURE : " . $DB->error);
                exit();
            }
            $DB_STMT_SESSION->bind_param("s", $session);
            $DB_STMT_SESSION->execute();
            if ($DB_STMT_SESSION->errno != 0) {
                # user session query error
                printOutput(-2, "SESSION QUERY FAILURE : ".$DB_STMT_SESSION->error);
                exit();
            }
            $DB_STMT_SESSION->bind_result($_validation["user_index"]);
            $DB_STMT_SESSION->store_result();
            # session is not valid
            if ($DB_STMT_SESSION->num_rows != 1) {
                printOutput(-3, "USER SESSION NOT VALID");
                exit();
            }
            $DB_STMT_SESSION->fetch();
            $DB_STMT_SESSION->close();
        } catch (Exception $e) {
            # user session query error
            printOutput(-2, "DB QUERY FAILURE : ".$DB->error);
            exit();
        }

        # execute session expire set query
        try {
            $DB_SQL_SESSION = "UPDATE `UserSession` SET `user_session_time` = NOW() WHERE `user_session_key` = ?";
            $DB_STMT_SESSION = $DB->prepare($DB_SQL_SESSION);
            # database query not ready
            if (!$DB_STMT_SESSION) {
                printOutput(-2, "DB QUERY FAILURE : " . $DB->error);
                exit();
            }
            $DB_STMT_SESSION->bind_param("s", $session);
            $DB_STMT_SESSION->execute();
            if ($DB_STMT_SESSION->errno != 0) {
                # user session expire set query error
                printOutput(-2, "SESSION UPDATE FAILURE : ".$DB_STMT_SESSION->error);
                exit();
            }
            $DB_STMT_SESSION->close();
        } catch (Exception $e) {
            # user session expire set query error
            printOutput(-2, "DB QUERY FAILURE : ".$DB->error);
            exit();
        }

        # execute user level query
        try {
            $DB_SQL_SESSION = "SELECT `user_id`, `user_name`, `user_level` FROM `Users` WHERE `user_index` = ?";
            $DB_STMT_SESSION = $DB->prepare($DB_SQL_SESSION);
            # database query not ready
            if (!$DB_STMT_SESSION) {
                printOutput(-2, "DB QUERY FAILURE : " . $DB->error);
                exit();
            }
            $DB_STMT_SESSION->bind_param("i", $_validation["user_index"]);
            $DB_STMT_SESSION->execute();
            if ($DB_STMT_SESSION->errno != 0) {
                printOutput(-2, "DB QUERY FAILURE : " . $DB->error);
                exit();
            }
            $DB_STMT_SESSION->bind_result($_validation["user_id"], $_validation["user_name"], $_validation["user_level"]);
            $DB_STMT_SESSION->store_result();
            if ($DB_STMT_SESSION->num_rows != 1) {
                printOutput(-3, "USER QUERY FAILURE");
                exit();
            }
            $DB_STMT_SESSION->fetch();
            $DB_STMT_SESSION->close();
        } catch (Exception $e) {
            # user session query error
            printOutput(-2, "DB QUERY FAILURE : ".$DB->error);
            exit();
        }

        return $_validation;
    }

    public function logout($user) {

    }

}

?>