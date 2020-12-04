<?php

class UserSession {

    const SESSION = "session";
    const USER_SESSION = "usersession";
    const USER_SESSION_INDEX = "user_session_index";
    const USER_SESSION_USER = "user_session_user";
    const USER_SESSION_KEY = "user_session_key";
    const USER_SESSION_TIME = "user_session_time";

    private $userSessionIndex;
    private $userSessionUser;
    private $userSessionKey;
    private $userSessionTime;

    public function __construct($params = null) {
        if (!isset($params)) return;
        $this->userSessionIndex = $params[self::USER_SESSION_INDEX];
        $this->userSessionUser = $params[self::USER_SESSION_USER];
        $this->userSessionKey = $params[self::USER_SESSION_KEY];
        $this->userSessionTime = $params[self::USER_SESSION_TIME];
    }

    /**
     * @return mixed
     */
    public function getUserSessionIndex()
    {
        return $this->userSessionIndex;
    }

    /**
     * @param mixed $userSessionIndex
     */
    public function setUserSessionIndex($userSessionIndex)
    {
        $this->userSessionIndex = $userSessionIndex;
    }

    /**
     * @return mixed
     */
    public function getUserSessionUser()
    {
        return $this->userSessionUser;
    }

    /**
     * @param mixed $userSessionUser
     */
    public function setUserSessionUser($userSessionUser)
    {
        $this->userSessionUser = $userSessionUser;
    }

    /**
     * @return mixed
     */
    public function getUserSessionKey()
    {
        return $this->userSessionKey;
    }

    /**
     * @param mixed $userSessionKey
     */
    public function setUserSessionKey($userSessionKey)
    {
        $this->userSessionKey = $userSessionKey;
    }

    /**
     * @return mixed
     */
    public function getUserSessionTime()
    {
        return $this->userSessionTime;
    }

    /**
     * @param mixed $userSessionTime
     */
    public function setUserSessionTime($userSessionTime)
    {
        $this->userSessionTime = $userSessionTime;
    }

}

?>