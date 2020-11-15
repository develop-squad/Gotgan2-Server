<?php

class LogType {

    const LOG_TYPE_INDEX = "log_type_index";
    const LOG_TYPE_NAME = "log_type_name";
    const LOG_TYPE_LEVEL = "log_type_level";

    private $logTypeIndex;
    private $logTypeName;
    private $logTypeLevel;

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