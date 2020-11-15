<?php

class Log {

    const LOG_INDEX = "log_index";
    const LOG_PRODUCT = "log_product";
    const LOG_USER = "log_user";
    const LOG_TYPE = "log_type";
    const LOG_TEXT = "log_text";
    const LOG_TIME = "log_time";

    private $logIndex;
    private $logProduct;
    private $logUser;
    private $logType;
    private $logText;
    private $logTime;

    public function __construct($params) {
        $this->logIndex = $params[self::LOG_INDEX];
        $this->logProduct = $params[self::LOG_PRODUCT];
        $this->logUser = $params[self::LOG_USER];
        $this->logType = $params[self::LOG_TYPE];
        $this->logText = $params[self::LOG_TEXT];
        $this->logTime = $params[self::LOG_TIME];
    }

    /**
     * @return mixed
     */
    public function getLogIndex()
    {
        return $this->logIndex;
    }

    /**
     * @param mixed $logIndex
     */
    public function setLogIndex($logIndex)
    {
        $this->logIndex = $logIndex;
    }

    /**
     * @return mixed
     */
    public function getLogProduct()
    {
        return $this->logProduct;
    }

    /**
     * @param mixed $logProduct
     */
    public function setLogProduct($logProduct)
    {
        $this->logProduct = $logProduct;
    }

    /**
     * @return mixed
     */
    public function getLogUser()
    {
        return $this->logUser;
    }

    /**
     * @param mixed $logUser
     */
    public function setLogUser($logUser)
    {
        $this->logUser = $logUser;
    }

    /**
     * @return mixed
     */
    public function getLogType()
    {
        return $this->logType;
    }

    /**
     * @param mixed $logType
     */
    public function setLogType($logType)
    {
        $this->logType = $logType;
    }

    /**
     * @return mixed
     */
    public function getLogText()
    {
        return $this->logText;
    }

    /**
     * @param mixed $logText
     */
    public function setLogText($logText)
    {
        $this->logText = $logText;
    }

    /**
     * @return mixed
     */
    public function getLogTime()
    {
        return $this->logTime;
    }

    /**
     * @param mixed $logTime
     */
    public function setLogTime($logTime)
    {
        $this->logTime = $logTime;
    }

}

?>