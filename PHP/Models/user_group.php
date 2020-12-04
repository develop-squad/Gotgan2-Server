<?php

class UserGroup {

    const USER_GROUP = "user_group";
    const USER_GROUP_INDEX = "user_group_index";
    const USER_GROUP_NAME = "user_group_name";
    const GROUPS = "groups";
    const GROUP_INDEX = "group_index";
    const GROUP_NAME = "group_name";

    private $userGroupIndex;
    private $userGroupName;

    public function __construct($params = null) {
        if (!isset($params)) return;
        $this->userGroupIndex = $params[self::USER_GROUP_INDEX];
        $this->userGroupName = $params[self::USER_GROUP_NAME];
    }

    /**
     * @return mixed
     */
    public function getUserGroupIndex()
    {
        return $this->userGroupIndex;
    }

    /**
     * @param mixed $userGroupIndex
     */
    public function setUserGroupIndex($userGroupIndex)
    {
        $this->userGroupIndex = $userGroupIndex;
    }

    /**
     * @return mixed
     */
    public function getUserGroupName()
    {
        return $this->userGroupName;
    }

    /**
     * @param mixed $userGroupName
     */
    public function setUserGroupName($userGroupName)
    {
        $this->userGroupName = $userGroupName;
    }

}

?>