<?php

class SystemManager extends Singleton {

    const SYSTEM_MASTER = "SYSTEM_ON";
    const SYSTEM_LOGIN = "SYSTEM_LOGIN";
    const SYSTEM_RENT = "SYSTEM_RENT";
    const SYSTEM_MESSAGE = "SYSTEM_MESSAGE";

    const SWITCH_MASTER = 1;
    const SWITCH_LOGIN = 2;
    const SWITCH_RENT = 3;
    const SWITCH_MESSAGE = 4;

    protected static $instance;

    private $systemMaster;
    private $systemLogin;
    private $systemRent;
    private $systemMessage;

    private function checkSystemSwitch($switchType) : int {
        # execute system switch query
        try {
            $DB_SQL = "SELECT `system_on`, `system_login`, `system_rent`, `system_message` FROM `System` LIMIT 1";
            $DB_STMT = $DB->prepare($DB_SQL);
            # database query not ready
            if (!$DB_STMT) {
                printOutput(-2, "DB QUERY FAILURE : ".$DB->error);
                exit();
            }
            $DB_STMT->execute();
            if ($DB_STMT->errno != 0) {
                # system switch query error
                printOutput(-4, "SYSTEM SWITCH FAILURE : ".$DB_STMT->error);
                exit();
            }
            $TEMP_SYSTEM_ON = 0;
            $TEMP_SYSTEM_LOGIN = 0;
            $TEMP_SYSTEM_RENT = 0;
            $TEMP_SYSTEM_MESSAGE = 0;
            $DB_STMT->bind_result($TEMP_SYSTEM_ON, $TEMP_SYSTEM_LOGIN, $TEMP_SYSTEM_RENT, $TEMP_SYSTEM_MESSAGE);
            $DB_STMT->fetch();
            $DB_STMT->close();
        } catch(Exception $e) {
            # system switch query error
            printOutput(-2, "DB QUERY FAILURE : ".$DB->error);
            exit();
        }

        if ($switchType == self::SWITCH_MASTER) {
            return $TEMP_SYSTEM_ON;
        }
        if ($switchType == self::SWITCH_LOGIN) {
            return $TEMP_SYSTEM_LOGIN;
        }
        if ($switchType == self::SWITCH_RENT) {
            return $TEMP_SYSTEM_RENT;
        }
        if ($switchType == self::SWITCH_MESSAGE) {
            return $TEMP_SYSTEM_MESSAGE;
        }
        return -1;
    }

    public function checkSystemMaster() : int {
        return $this->checkSystemSwitch(self::SWITCH_MASTER);
    }

    public function checkSystemLogin() : int {
        return $this->checkSystemSwitch(self::SWITCH_LOGIN);
    }

    public function checkSystemRent() : int {
        return $this->checkSystemSwitch(self::SWITCH_RENT);
    }

    public function checkSystemMessage() : int {
        return $this->checkSystemSwitch(self::SWITCH_MESSAGE);
    }

    public function switchSystemMaster($masterSwitch) {

    }

    public function switchSystemLogin($loginSwitch) {

    }

    public function switchSystemRent($rentSwitch) {

    }

    public function switchSystemMessage($messageSwitch) {

    }

    public function sendEmail($userEmail, $title, $message) {
        $headers =  'MIME-Version: 1.0' . "\r\n";
        $headers .= 'From: DEVX <no-reply@devx.kr>' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        mail($userEmail, $title, $message, $headers);
    }

}

?>