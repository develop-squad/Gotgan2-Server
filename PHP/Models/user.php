<?php

class User {

    const USER_INDEX = "user_index";
    const USER_ID = "user_id";
    const USER_PW = "user_pw";
    const USER_LEVEL = "user_level";
    const USER_NAME = "user_name";
    const USER_SID = "user_sid";
    const USER_BLOCK = "user_block";
    const USER_UUID = "user_uuid";
    const USER_GROUP = "user_group";
    const USER_EMAIL = "user_email";
    const USER_PHONE = "user_phone";
    const USER_CREATED = "user_created";
    const USER_SESSION = "user_session";

    private $userIndex;
    private $userID;
    private $userPW;
    private $userLevel;
    private $userName;
    private $userSid;
    private $userBlock;
    private $userUuid;
    private $userGroup;
    private $userEmail;
    private $userPhone;
    private $userCreated;
    private $userSession;

    public function __construct($params) {
        $this->userIndex = $params[self::USER_INDEX];
        $this->userID = $params[self::USER_ID];
        $this->userPW = $params[self::USER_PW];
        $this->userLevel = $params[self::USER_LEVEL];
        $this->userName = $params[self::USER_NAME];
        $this->userSid = $params[self::USER_SID];
        $this->userBlock = $params[self::USER_BLOCK];
        $this->userUuid = $params[self::USER_UUID];
        $this->userGroup = $params[self::USER_GROUP];
        $this->userEmail = $params[self::USER_EMAIL];
        $this->userPhone = $params[self::USER_PHONE];
        $this->userCreated = $params[self::USER_CREATED];
        $this->userSession = $params[self::USER_SESSION];
    }

    /**
     * @return mixed
     */
    public function getUserIndex()
    {
        return $this->userIndex;
    }

    /**
     * @param mixed $userIndex
     */
    public function setUserIndex($userIndex)
    {
        $this->userIndex = $userIndex;
    }

    /**
     * @return mixed
     */
    public function getUserID()
    {
        return $this->userID;
    }

    /**
     * @param mixed $userID
     */
    public function setUserID($userID)
    {
        $this->userID = $userID;
    }

    /**
     * @return mixed
     */
    public function getUserPW()
    {
        return $this->userPW;
    }

    /**
     * @param mixed $userPW
     */
    public function setUserPW($userPW)
    {
        $this->userPW = $userPW;
    }

    /**
     * @return mixed
     */
    public function getUserLevel()
    {
        return $this->userLevel;
    }

    /**
     * @param mixed $userLevel
     */
    public function setUserLevel($userLevel)
    {
        $this->userLevel = $userLevel;
    }

    /**
     * @return mixed
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @param mixed $userName
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * @return mixed
     */
    public function getUserSid()
    {
        return $this->userSid;
    }

    /**
     * @param mixed $userSid
     */
    public function setUserSid($userSid)
    {
        $this->userSid = $userSid;
    }

    /**
     * @return mixed
     */
    public function getUserBlock()
    {
        return $this->userBlock;
    }

    /**
     * @param mixed $userBlock
     */
    public function setUserBlock($userBlock)
    {
        $this->userBlock = $userBlock;
    }

    /**
     * @return mixed
     */
    public function getUserUuid()
    {
        return $this->userUuid;
    }

    /**
     * @param mixed $userUuid
     */
    public function setUserUuid($userUuid)
    {
        $this->userUuid = $userUuid;
    }

    /**
     * @return mixed
     */
    public function getUserGroup()
    {
        return $this->userGroup;
    }

    /**
     * @param mixed $userGroup
     */
    public function setUserGroup($userGroup)
    {
        $this->userGroup = $userGroup;
    }

    /**
     * @return mixed
     */
    public function getUserEmail()
    {
        return $this->userEmail;
    }

    /**
     * @param mixed $userEmail
     */
    public function setUserEmail($userEmail)
    {
        $this->userEmail = $userEmail;
    }

    /**
     * @return mixed
     */
    public function getUserPhone()
    {
        return $this->userPhone;
    }

    /**
     * @param mixed $userPhone
     */
    public function setUserPhone($userPhone)
    {
        $this->userPhone = $userPhone;
    }

    /**
     * @return mixed
     */
    public function getUserCreated()
    {
        return $this->userCreated;
    }

    /**
     * @param mixed $userCreated
     */
    public function setUserCreated($userCreated)
    {
        $this->userCreated = $userCreated;
    }

    /**
     * @return mixed
     */
    public function getUserSession()
    {
        return $this->userSession;
    }

    /**
     * @param mixed $userSession
     */
    public function setUserSession($userSession)
    {
        $this->userSession = $userSession;
    }

}

?>