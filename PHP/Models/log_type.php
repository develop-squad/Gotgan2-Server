<?php

class LogType {

    const LOG_TYPE_INDEX = "log_type_index";
    const LOG_TYPE_NAME = "log_type_name";
    const LOG_TYPE_LEVEL = "log_type_level";

    private $logTypeIndex;
    private $logTypeName;
    private $logTypeLevel;

    const TYPE_LOG_NORMAL = 1;
    const TYPE_LOG_IMPORTANT = 2;
    const TYPE_LOG_CRITICAL = 3;
    const TYPE_LOGIN = 4;
    const TYPE_USER_ADD = 5;
    const TYPE_USER_MODIFY = 6;
    const TYPE_USER_DELETE = 7;
    const TYPE_USER_GROUP_ADD = 8;
    const TYPE_USER_GROUP_MODIFY = 9;
    const TYPE_USER_GROUP_DELETE = 10;
    const TYPE_PRODUCT_ADD = 11;
    const TYPE_PRODUCT_MODIFY = 12;
    const TYPE_PRODUCT_DELETE = 13;
    const TYPE_PRODUCT_GROUP_ADD= 14;
    const TYPE_PRODUCT_GROUP_MODIFY = 15;
    const TYPE_PRODUCT_GROUP_DELETE = 16;
    const TYPE_RENT_ADD = 17;
    const TYPE_RENT_ALLOW = 18;
    const TYPE_RENT_RETURN = 19;
    const TYPE_RENT_DELETE = 20;
    const TYPE_SEND_MAIL = 21;
    const TYPE_SEND_FCM = 22;

    public function __construct($params) {
        $this->logTypeIndex = $params[self::LOG_TYPE_INDEX];
        $this->logTypeName = $params[self::LOG_TYPE_NAME];
        $this->logTypeLevel = $params[self::LOG_TYPE_LEVEL];
    }

    /**
     * @return mixed
     */
    public function getLogTypeIndex()
    {
        return $this->logTypeIndex;
    }

    /**
     * @param mixed $logTypeIndex
     */
    public function setLogTypeIndex($logTypeIndex)
    {
        $this->logTypeIndex = $logTypeIndex;
    }

    /**
     * @return mixed
     */
    public function getLogTypeName()
    {
        return $this->logTypeName;
    }

    /**
     * @param mixed $logTypeName
     */
    public function setLogTypeName($logTypeName)
    {
        $this->logTypeName = $logTypeName;
    }

    /**
     * @return mixed
     */
    public function getLogTypeLevel()
    {
        return $this->logTypeLevel;
    }

    /**
     * @param mixed $logTypeLevel
     */
    public function setLogTypeLevel($logTypeLevel)
    {
        $this->logTypeLevel = $logTypeLevel;
    }

}

?>