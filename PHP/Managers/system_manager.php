<?php

class SystemManager extends Singleton {

    const SYSTEM = "SYSTEM";
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
            $dbQuery = "SELECT `".self::SYSTEM_MASTER."`, `".self::SYSTEM_LOGIN."`, `".self::SYSTEM_RENT."`, `".self::SYSTEM_MESSAGE."` FROM `".self::SYSTEM."` LIMIT 1";
            $db = DatabaseManager::getInstance()->getConnection();
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # system switch query error
                printOutput(-4, "SYSTEM SWITCH FAILURE : ".$dbStatement->error);
                exit();
            }
            $systemMaster = 0;
            $systemLogin = 0;
            $systemRent = 0;
            $systemMessage = 0;
            $dbStatement->bind_result($systemMaster, $systemLogin, $systemRent, $systemMessage);
            $dbStatement->fetch();
            $dbStatement->close();
        } catch(Exception $e) {
            # system switch query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        if ($switchType == self::SWITCH_MASTER) {
            return $systemMaster;
        }
        if ($switchType == self::SWITCH_LOGIN) {
            return $systemLogin;
        }
        if ($switchType == self::SWITCH_RENT) {
            return $systemRent;
        }
        if ($switchType == self::SWITCH_MESSAGE) {
            return $systemMessage;
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

    private function switchSystemSwitch($switchType, $switchValue) {
        # execute system switch query
        try {
            $dbQuery = "";
            if ($switchType == self::SWITCH_MASTER) {
                $dbQuery = "UPDATE `System` SET `system_on` = ".$switchValue;
            }
            if ($switchType == self::SWITCH_LOGIN) {
                $dbQuery = "UPDATE `System` SET `system_login` = ".$switchValue;
            }
            if ($switchType == self::SWITCH_RENT) {
                $dbQuery = "UPDATE `System` SET `system_rent` = ".$switchValue;
            }
            if ($switchType == self::SWITCH_MESSAGE) {
                $dbQuery = "UPDATE `System` SET `system_message` = ".$switchValue;
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
                # system switch query error
                printOutput(-4, "SYSTEM SWITCH FAILURE : ".$dbStatement->error);
                exit();
            }
            $dbStatement->close();
        } catch(Exception $e) {
            # system switch query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }
    }

    public function switchSystemMaster($masterSwitch) {
        self::switchSystemSwitch(self::SWITCH_MASTER, $masterSwitch);
    }

    public function switchSystemLogin($loginSwitch) {
        self::switchSystemSwitch(self::SWITCH_LOGIN, $loginSwitch);
    }

    public function switchSystemRent($rentSwitch) {
        self::switchSystemSwitch(self::SWITCH_RENT, $rentSwitch);
    }

    public function switchSystemMessage($messageSwitch) {
        self::switchSystemSwitch(self::SWITCH_MESSAGE, $messageSwitch);
    }

    public function sendEmail($userEmail, $title, $message) {
        $headers =  'MIME-Version: 1.0' . "\r\n";
        $headers .= 'From: DEVX <no-reply@devx.kr>' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        mail($userEmail, $title, $message, $headers);
    }

    public function sendEmailByApi($title, $content, $params, $validation = null) {
        # execute user email query
        try {
            $dbQuery = "SELECT `user_id`, `user_email` FROM `Users` WHERE 1=1";
            if (isset($params[User::USER_INDEX])) {
                $dbQuery .= " AND `user_index` = ".$params[User::USER_INDEX];
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
                printOutput(-2, "DB QUERY FAILURE : " . $db->error);
                exit();
            }
            $mailData = array();
            $dbStatement->bind_result($mailData[User::USER_ID], $mailData[User::USER_EMAIL]);
            while ($dbStatement->fetch()) {
                SystemManager::getInstance()->sendEmail($mailData[User::USER_EMAIL], $title, $content);
            }
            $dbStatement->close();
        } catch (Exception $e) {
            # user email query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        # email log
        if (isset($validation)) {
            LogManager::getInstance()->addLog([LOG::LOG_USER => $validation[User::USER_INDEX], LOG::LOG_TYPE => LogType::TYPE_SEND_MAIL]);
        }
    }

}

?>