<?php

class ServiceManager extends Singleton {

    protected static $instance;

    public function loginAccount($params) : User {
        $user = new User();

        # execute login query
        try {
            $dbQuery = "SELECT `U`.`user_index`, `U`.`user_id`, `U`.`user_pw`, 
                `U`.`user_level`, `U`.`user_name`, `U`.`user_sid`, `U`.`user_block`, 
                `U`.`user_group` as `user_group_index`, `UG`.`user_group_name`, 
                `U`.`user_email`, `U`.`user_phone`, `U`.`user_created` 
                FROM `Users` AS `U` LEFT OUTER JOIN `UserGroup` AS `UG` ON (`U`.`user_group` = `UG`.`user_group_index`) 
                WHERE `user_id` = ?";
            $db = DatabaseManager::getInstance()->getConnection();
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->bind_param("s", $params[User::USER_ID]);
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                printOutput(-2, "DB QUERY FAILURE : " . $dbStatement->error);
                exit();
            }
            $userIndex = 0;
            $userID = "";
            $userPW = "";
            $userLevel = 0;
            $userName = "";
            $userSid = "";
            $userBlock = 0;
            $userGroupIndex = 0;
            $userGroupName = "";
            $userEmail = "";
            $userPhone = "";
            $userCreated = "";
            $dbStatement->bind_result($userIndex, $userID, $userPW, $userLevel, $userName, $userSid, $userBlock, $userGroupIndex, $userGroupName, $userEmail, $userPhone, $userCreated);
            $dbStatement->store_result();
            if ($dbStatement->num_rows != 1) {
                printOutput(-3, "LOGIN FAILURE");
                exit();
            }
            $dbStatement->fetch();
            $dbStatement->close();
            $user->setUserIndex($userIndex);
            $user->setUserID($userID);
            $user->setUserPW($userPW);
            $user->setUserLevel($userLevel);
            $user->setUserName($userName);
            $user->setUserSid($userSid);
            $user->setUserBlock($userBlock);
            $user->setUserGroup($userGroupIndex);
            $user->setUserEmail($userEmail);
            $user->setUserPhone($userPhone);
            $user->setUserCreated($userCreated);
        } catch(Exception $e) {
            # login query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        # password matching
        if (!password_verify($params[User::USER_PW], $userPW)) {
            printOutput(-3, "LOGIN FAILURE");
            exit();
        }

        if ($userLevel < 2) {
            if (!SystemManager::getInstance()->checkSystemMaster()) {
                printOutput(-3, "SYSTEM SWITCH IS OFF");
                exit();
            }
            if (!SystemManager::getInstance()->checkSystemLogin()) {
                printOutput(-3, "LOGIN SWITCH IS OFF");
                exit();
            }
        }

        if ($userLevel < 2 && $userBlock > 0) {
            printOutput(-3, "LOGIN FAILURE : BANNED ".$userBlock." DAYS");
            exit();
        }

        $sessionKey = generateSession();
        # execute user session creation query for not blocked user
        try {
            $dbQuery = "INSERT INTO `UserSession` (`user_session_user`, `user_session_key`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `user_session_key` = ?, `user_session_time` = now()";
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : " . $db->error);
                exit();
            }
            $dbStatement->bind_param("iss", $userIndex, $sessionKey, $sessionKey);
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                printOutput(-2, "DB QUERY FAILURE : " . $dbStatement->error);
                exit();
            }
            $dbStatement->close();
        } catch (Exception $e) {
            # user session query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }
        $user->setUserSession($sessionKey);

        return $user;
    }

    public function loginSession($validation) : User {
        $user = new User();

        # execute login query
        try {
            $dbQuery = "SELECT `U`.`user_index`, `U`.`user_id`, `U`.`user_pw`, 
                `U`.`user_level`, `U`.`user_name`, `U`.`user_sid`, `U`.`user_block`, 
                `U`.`user_group` as `user_group_index`, `UG`.`user_group_name`, 
                `U`.`user_email`, `U`.`user_phone`, `U`.`user_created` 
                FROM `Users` AS `U` LEFT OUTER JOIN `UserGroup` AS `UG` ON (`U`.`user_group` = `UG`.`user_group_index`) 
                WHERE `user_index` = ?";
            $db = DatabaseManager::getInstance()->getConnection();
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->bind_param("i", $validation["user_index"]);
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                printOutput(-2, "DB QUERY FAILURE : " . $dbStatement->error);
                exit();
            }
            $userIndex = 0;
            $userID = "";
            $userPW = "";
            $userLevel = 0;
            $userName = "";
            $userSid = "";
            $userBlock = 0;
            $userGroupIndex = 0;
            $userGroupName = "";
            $userEmail = "";
            $userPhone = "";
            $userCreated = "";
            $dbStatement->bind_result($userIndex, $userID, $userPW, $userLevel, $userName, $userSid, $userBlock, $userGroupIndex, $userGroupName, $userEmail, $userPhone, $userCreated);
            $dbStatement->store_result();
            if ($dbStatement->num_rows != 1) {
                printOutput(-3, "LOGIN FAILURE");
                exit();
            }
            $dbStatement->fetch();
            $dbStatement->close();
            $user->setUserIndex($userIndex);
            $user->setUserID($userID);
            $user->setUserPW($userPW);
            $user->setUserLevel($userLevel);
            $user->setUserName($userName);
            $user->setUserSid($userSid);
            $user->setUserBlock($userBlock);
            $user->setUserGroup($userGroupIndex);
            $user->setUserEmail($userEmail);
            $user->setUserPhone($userPhone);
            $user->setUserCreated($userCreated);
            $user->setUserSession($validation[User::USER_SESSION]);
        } catch(Exception $e) {
            # login query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        if ($userLevel < 2) {
            if (!SystemManager::getInstance()->checkSystemMaster()) {
                printOutput(-3, "SYSTEM SWITCH IS OFF");
                exit();
            }
            if (!SystemManager::getInstance()->checkSystemLogin()) {
                printOutput(-3, "LOGIN SWITCH IS OFF");
                exit();
            }
        }

        if ($userLevel < 2 && $userBlock > 0) {
            printOutput(-3, "LOGIN FAILURE : BANNED ".$userBlock." DAYS");
            exit();
        }

        $userIndex = $validation[User::USER_INDEX];

        if (isset($params[User::USER_UUID])) {
            $user->setUserUuid($params[User::USER_UUID]);
            # update UUID
            try {
                $dbQuery = "UPDATE `Users` SET `user_uuid` = ? WHERE `user_index` = ?";
                $dbStatement = $db->prepare($dbQuery);
                # database query not ready
                if (!$dbStatement) {
                    printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                    exit();
                }
                $dbStatement->bind_param("si", $params[User::USER_UUID], $userIndex);
                $dbStatement->execute();
                if ($dbStatement->errno != 0) {
                    # user uuid update query error
                    printOutput(-4, "UUID UPDATE FAILURE : ".$dbStatement->error);
                    exit();
                }
                $dbStatement->close();
            } catch(Exception $e) {
                # user uuid update query error
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $user->setUserUuid($params[User::USER_UUID]);
        }

        return $user;
    }

    public function validateSession($sessionKey) {
        $user = new User();
        $user->setUserSession($sessionKey);

        # execute session validate query
        try {
            $dbQuery = "SELECT `".UserSession::USER_SESSION_USER."` FROM `".UserSession::USER_SESSION."` WHERE `".UserSession::USER_SESSION_KEY."` = ?";
            $db = DatabaseManager::getInstance()->getConnection();
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : " . $db->error);
                exit();
            }
            $dbStatement->bind_param("s", $sessionKey);
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # user session query error
                printOutput(-2, "SESSION QUERY FAILURE : ".$dbStatement->error);
                exit();
            }
            $userIndex = 0;
            $dbStatement->bind_result($userIndex);
            $dbStatement->store_result();
            # session is not valid
            if ($dbStatement->num_rows != 1) {
                printOutput(-3, "USER SESSION NOT VALID");
                exit();
            }
            $dbStatement->fetch();
            $dbStatement->close();
            $user->setUserIndex($userIndex);
        } catch (Exception $e) {
            # user session query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        # execute session expire set query
        try {
            $dbQuery = "UPDATE `".UserSession::USER_SESSION."` SET `".UserSession::USER_SESSION_TIME."` = NOW() WHERE `".UserSession::USER_SESSION_KEY."` = ?";
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : " . $db->error);
                exit();
            }
            $dbStatement->bind_param("s", $sessionKey);
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # user session expire set query error
                printOutput(-2, "SESSION UPDATE FAILURE : ".$dbStatement->error);
                exit();
            }
            $dbStatement->close();
        } catch (Exception $e) {
            # user session expire set query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        # execute user level query
        try {
            $dbQuery = "SELECT `".User::USER_ID."`, `".User::USER_NAME."`, `".User::USER_LEVEL."` FROM `".User::USER."` WHERE `".User::USER_INDEX."` = ?";
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : " . $db->error);
                exit();
            }
            $dbStatement->bind_param("i", $user->getUserIndex());
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                printOutput(-2, "DB QUERY FAILURE : " . $db->error);
                exit();
            }
            $userID = "";
            $userName = "";
            $userLevel = 0;
            $dbStatement->bind_result($userID, $userName, $userLevel);
            $dbStatement->store_result();
            if ($dbStatement->num_rows != 1) {
                printOutput(-3, "USER QUERY FAILURE");
                exit();
            }
            $dbStatement->fetch();
            $dbStatement->close();
            $user->setUserID($userID);
            $user->setUserName($userName);
            $user->setUserLevel($userLevel);
        } catch (Exception $e) {
            # user session query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }

        return [User::USER_INDEX => $user->getUserIndex(), User::USER_LEVEL => $user->getUserLevel(), User::USER_ID => $user->getUserID(), User::USER_NAME => $user->getUserName(), User::USER_SESSION => $user->getUserSession()];
    }

    public function updateUUID(User $user, $uuid) {
        $user->setUserUuid($uuid);
        # update UUID
        try {
            $dbQuery = "UPDATE `Users` SET `user_uuid` = ? WHERE `user_index` = ?";
            $db = DatabaseManager::getInstance()->getConnection();
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->bind_param("si", $uuid, $user->getUserIndex());
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # user uuid update query error
                printOutput(-4, "UUID UPDATE FAILURE : ".$dbStatement->error);
                exit();
            }
            $dbStatement->close();
        } catch(Exception $e) {
            # user uuid update query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }
    }

    public function logout($session) {
        # execute logout query
        try {
            $dbQuery = "DELETE FROM `usersession` WHERE `user_session_key` = ?";
            $db = DatabaseManager::getInstance()->getConnection();
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->bind_param("s", $session);
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                printOutput(-2, "DB QUERY FAILURE : " . $dbStatement->error);
                exit();
            }
            $dbStatement->close();
        } catch(Exception $e) {
            # logout query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }
    }

    public function checkSessionExpires($params = null) {
        # execute session expire query
        try {
            $dbQuery = "DELETE FROM `usersession` WHERE `user_session_time` < DATE_SUB(NOW(), INTERVAL 7 DAY)";
            $db = DatabaseManager::getInstance()->getConnection();
            $dbStatement = $db->prepare($dbQuery);
            # database query not ready
            if (!$dbStatement) {
                printOutput(-2, "DB QUERY FAILURE : ".$db->error);
                exit();
            }
            $dbStatement->execute();
            if ($dbStatement->errno != 0) {
                # session expire query error
                printOutput(-4, "SESSION EXPIRE FAILURE : ".$dbStatement->error);
                exit();
            }
            $dbStatement->close();
        } catch(Exception $e) {
            # session expire query error
            printOutput(-2, "DB QUERY FAILURE : ".$db->error);
            exit();
        }
    }

}

?>