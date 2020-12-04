<?php

class UserManager extends Singleton {

    protected static $instance;

    public function getUsers($params, $validation = null) {
        # prepare user list result
        $userJsonResult = array();

        # execute user list query
        try {
            $DB_SQL = "
                SELECT `U`.`user_index`,
                    `U`.`user_id`,
                    `U`.`user_level`,
                    `U`.`user_name`,
                    `U`.`user_sid`,
                    `U`.`user_block`,
                    `U`.`user_uuid`,
                    `U`.`user_group` as `user_group_index`,
                    `UG`.`user_group_name` as `user_group_name`,
                    `U`.`user_email`,
                    `U`.`user_phone`,
                    `U`.`user_created`
                FROM `Users` AS `U` 
                    LEFT OUTER JOIN `UserGroup` AS `UG` ON (`U`.`user_group` = `UG`.`user_group_index`) 
                WHERE 1=1";
            if (isset($params[User::USER_INDEX])) {
                $DB_SQL .= " AND `user_index` = ".$params[User::USER_INDEX];
            }
            if (isset($params[User::USER_GROUP])) {
                $DB_SQL .= " AND `user_group` = ".$params[User::USER_GROUP];
            }
            $DB = DatabaseManager::getInstance()->getConnection();
            $DB_STMT = $DB->prepare($DB_SQL);
            # database query not ready
            if (!$DB_STMT) {
                printOutput(-2, "DB QUERY FAILURE : ".$DB->error);
                exit();
            }
            $DB_STMT->execute();
            if ($DB_STMT->errno != 0) {
                # user list query error
                printOutput(-4, "USER LIST FAILURE : ".$DB_STMT->error);
                exit();
            }
            $userData = array();
            $DB_STMT->bind_result($userData[User::USER_INDEX], $userData[User::USER_ID], $userData[User::USER_LEVEL], $userData[User::USER_NAME], $userData[User::USER_SID], $userData[User::USER_BLOCK], $userData[User::USER_UUID], $userData[User::USER_GROUP], $userData[UserGroup::USER_GROUP_NAME], $userData[User::USER_EMAIL], $userData[User::USER_PHONE], $userData[User::USER_CREATED]);
            while($DB_STMT->fetch()) {
                $userJsonObject = array();
                $userJsonObject["user_index"] = $userData[User::USER_INDEX];
                $userJsonObject["user_id"] = $userData[User::USER_ID];
                $userJsonObject["user_level"] = $userData[User::USER_LEVEL];
                $userJsonObject["user_name"] = $userData[User::USER_NAME];
                if (isset($userData[User::USER_SID])) {
                    $userJsonObject["user_sid"] = $userData[User::USER_SID];
                }
                if (isset($userData[User::USER_BLOCK])) {
                    $userJsonObject["user_block"] = $userData[User::USER_BLOCK];
                }
                if (isset($userData[User::USER_UUID])) {
                    $userJsonObject["user_uuid"] = $userData[User::USER_UUID];
                }
                $userJsonObject["user_group_index"] = $userData[User::USER_GROUP];
                if (isset($userData[UserGroup::USER_GROUP_NAME])) {
                    $userJsonObject["user_group_name"] = $userData[UserGroup::USER_GROUP_NAME];
                } else {
                    $userJsonObject["user_group_name"] = "";
                }
                if (isset($userData[User::USER_EMAIL])) {
                    $userJsonObject["user_email"] = $userData[User::USER_EMAIL];
                }
                if (isset($userData[User::USER_PHONE])) {
                    $userJsonObject["user_phone"] = $userData[User::USER_PHONE];
                }
                $userJsonObject["user_created"] = $userData[User::USER_CREATED];
                array_push($userJsonResult, $userJsonObject);
            }
            $DB_STMT->close();
        } catch(Exception $e) {
            # user list query error
            printOutput(-2, "DB QUERY FAILURE : ".$DB->error);
            exit();
        }

        return $userJsonResult;
    }

    public function getUser() {
        // $userIndex
        // $userSession
    }

    public function addUser($params, $validation = null) {
        # execute user creation query
        try {
            $dbQuery = "INSERT INTO `Users` (`user_id`, `user_pw`, `user_level`, `user_name`, `user_sid`, `user_block`, `user_uuid`, `user_group`, `user_email`, `user_phone`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $db = DatabaseManager::getInstance()->getConnection();
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->bind_param("ssissisiss", $params[User::USER_ID], $params[User::USER_PW], $params[User::USER_LEVEL], $params[User::USER_NAME], $params[User::USER_SID], $params[User::USER_BLOCK], $params[User::USER_UUID], $params[User::USER_GROUP], $params[User::USER_EMAIL], $params[User::USER_PHONE]);
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # user creation query error
                printOutput(-4, "ADD USER FAILURE : ".$dbStatement->error);
                exit();
            }
            $userIndex = $dbStatement->insert_id;
            $dbStatement->close();
        } catch(Exception $e) {
            # user creation query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        if (isset($validation)) {
            LogManager::getInstance()->addLog([LOG::LOG_USER => $validation[User::USER_INDEX], LOG::LOG_TYPE => LogType::TYPE_USER_ADD]);
        } else {
            LogManager::getInstance()->addLog([LOG::LOG_USER => $userIndex, LOG::LOG_TYPE => LogType::TYPE_USER_ADD]);
        }

    }

    public function deleteUser($params, $validation = null) {
        # execute user deletion query
        try {
            $dbQuery = "DELETE FROM `Users` WHERE `user_index` = ?";
            $db = DatabaseManager::getInstance()->getConnection();
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->bind_param("i", $params[User::USER_INDEX]);
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # user deletion query error
                printOutput(-4, "DELETE USER FAILURE : ".$dbStatement->error);
                exit();
            }
            $dbStatement->close();
        } catch(Exception $e) {
            # user deletion query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        # user deletion log
        LogManager::getInstance()->addLog([LOG::LOG_USER => $validation[User::USER_INDEX], LOG::LOG_TYPE => LogType::TYPE_USER_DELETE]);

    }

    private function executeModifyUser(int $userIndex, string $modifyQuery, string $bindType, $bindValue) {
        # execute user modification query
        try {
            $db = DatabaseManager::getInstance()->getConnection();
            $dbStatement = $db->prepare($modifyQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->bind_param($bindType, $bindValue, $userIndex);
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # user modification query error
                printOutput(-4, "MODIFY USER FAILURE : ".$dbStatement->error);
                exit();
            }
            $dbStatement->close();
        } catch(Exception $e) {
            # user modification query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }
    }

    public function modifyUser($params, $validation = null) {
        if (isset($params[User::USER_ID])) {
            $idModifyQuery = "UPDATE `Users` SET `user_id` = ? WHERE `user_index` = ?";
            self::executeModifyUser($params[User::USER_INDEX], $idModifyQuery, "si", $params[User::USER_ID]);
        }
        if (isset($params[User::USER_PW])) {
            $pwModifyQuery = "UPDATE `Users` SET `user_pw` = ? WHERE `user_index` = ?";
            self::executeModifyUser($params[User::USER_INDEX], $pwModifyQuery, "si", $params[User::USER_PW]);
        }
        if (isset($params[User::USER_LEVEL])) {
            $levelModifyQuery = "UPDATE `Users` SET `user_level` = ? WHERE `user_index` = ?";
            self::executeModifyUser($params[User::USER_INDEX], $levelModifyQuery, "ii", $params[User::USER_LEVEL]);
        }
        if (isset($params[User::USER_NAME])) {
            $nameModifyQuery = "UPDATE `Users` SET `user_name` = ? WHERE `user_index` = ?";
            self::executeModifyUser($params[User::USER_INDEX], $nameModifyQuery, "si", $params[User::USER_NAME]);
        }
        if (isset($params[User::USER_GROUP])) {
            $groupModifyQuery = "UPDATE `Users` SET `user_group` = ? WHERE `user_index` = ?";
            self::executeModifyUser($params[User::USER_INDEX], $groupModifyQuery, "ii", $params[User::USER_GROUP]);
        }
        if (isset($params[User::USER_SID])) {
            $sidModifyQuery = "UPDATE `Users` SET `user_sid` = ? WHERE `user_index` = ?";
            self::executeModifyUser($params[User::USER_SID], $sidModifyQuery, "si", $params[User::USER_SID]);
        }
        if (isset($params[User::USER_BLOCK])) {
            $blockModifyQuery = "UPDATE `Users` SET `user_block` = ? WHERE `user_index` = ?";
            self::executeModifyUser($params[User::USER_INDEX], $blockModifyQuery, "ii", $params[User::USER_BLOCK]);
        }
        if (isset($params[User::USER_UUID])) {
            $uuidModifyQuery = "UPDATE `Users` SET `user_uuid` = ? WHERE `user_index` = ?";
            self::executeModifyUser($params[User::USER_INDEX], $uuidModifyQuery, "si", $params[User::USER_UUID]);
        }
        if (isset($params[User::USER_EMAIL])) {
            $emailModifyQuery = "UPDATE `Users` SET `user_email` = ? WHERE `user_index` = ?";
            self::executeModifyUser($params[User::USER_EMAIL], $emailModifyQuery, "si", $params[User::USER_EMAIL]);
        }
        if (isset($params[User::USER_PHONE])) {
            $phoneModifyQuery = "UPDATE `Users` SET `user_phone` = ? WHERE `user_index` = ?";
            self::executeModifyUser($params[User::USER_INDEX], $phoneModifyQuery, "si", $params[User::USER_PHONE]);
        }

        # user modification log
        LogManager::getInstance()->addLog([LOG::LOG_USER => $validation[User::USER_INDEX], LOG::LOG_TYPE => LogType::TYPE_USER_MODIFY]);
    }

    public function getUserGroups($validation = null) {
        # prepare user group list result
        $userGroupJsonResult = array();

        # execute user group list query
        try {
            $dbQuery = "SELECT `user_group_index`, `user_group_name` FROM `UserGroup`";
            $db = DatabaseManager::getInstance()->getConnection();
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # user group list query error
                printOutput(-4, "USER GROUP LIST FAILURE : ".$dbStatement->error);
                exit();
            }
            $userGroupData = array();
            $dbStatement->bind_result($userGroupData[UserGroup::GROUP_INDEX], $userGroupData[UserGroup::GROUP_NAME]);
            while($dbStatement->fetch()) {
                $userGroupJsonObject = array();
                $userGroupJsonObject[UserGroup::GROUP_INDEX] = $userGroupData[UserGroup::GROUP_INDEX];
                $userGroupJsonObject[UserGroup::GROUP_NAME] = $userGroupData[UserGroup::GROUP_NAME];
                array_push($userGroupJsonResult, $userGroupJsonObject);
            }
            $dbStatement->close();
        } catch(Exception $e) {
            # user group list query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        return $userGroupJsonResult;
    }

    public function addUserGroup($params, $validation) {
        # execute user group creation query
        try {
            $dbQuery = "INSERT INTO `UserGroup` (`user_group_name`) VALUES (?)";
            $db = DatabaseManager::getInstance()->getConnection();
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->bind_param("s", $params[UserGroup::USER_GROUP_NAME]);
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # user group creation query error
                printOutput(-4, "ADD USER GROUP FAILURE : ".$dbStatement->error);
                exit();
            }
            $userGroupIndex = $dbStatement->insert_id;
            $dbStatement->close();
        } catch(Exception $e) {
            # user group creation query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        # user group creation log
        LogManager::getInstance()->addLog([LOG::LOG_USER => $validation[User::USER_INDEX], LOG::LOG_TYPE => LogType::TYPE_USER_GROUP_ADD]);

        return $userGroupIndex;
    }

    public function deleteUserGroup($params, $validation = null) {
        # execute user group deletion query
        try {
            $dbQuery = "DELETE FROM `UserGroup` WHERE `user_group_index` = ?";
            $db = DatabaseManager::getInstance()->getConnection();
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->bind_param("i", $params[UserGroup::USER_GROUP_INDEX]);
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # user group deletion query error
                printOutput(-4, "DELETE USER GROUP FAILURE : ".$dbStatement->error);
                exit();
            }
            $dbStatement->close();
        } catch(Exception $e) {
            # user group deletion query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        # user group deletion log
        LogManager::getInstance()->addLog([LOG::LOG_USER => $validation[User::USER_INDEX], LOG::LOG_TYPE => LogType::TYPE_USER_GROUP_DELETE]);

    }

    private function executeModifyUserGroup(int $groupIndex, string $modifyQuery, string $bindType, $bindValue) {
        # execute user group modification query
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
                # user group modification query error
                printOutput(-4, "MODIFY USER GROUP FAILURE : ".$dbStatement->error);
                exit();
            }
            $dbStatement->close();
        } catch(Exception $e) {
            # user group modification query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }
    }

    public function modifyUserGroup($params, $validation) {
        if (isset($params[UserGroup::USER_GROUP_NAME])) {
            $nameModifyQuery = "UPDATE `UserGroup` SET `user_group_name` = ? WHERE `user_group_index` = ?";
            $this->executeModifyUserGroup($params[UserGroup::USER_GROUP_INDEX], $nameModifyQuery, "si", $params[UserGroup::USER_GROUP_NAME]);
        }

        # user group modification log
        LogManager::getInstance()->addLog([LOG::LOG_USER => $validation[User::USER_INDEX], LOG::LOG_TYPE => LogType::TYPE_USER_GROUP_MODIFY]);

    }

}

?>