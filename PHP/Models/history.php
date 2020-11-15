<?php

class History {

    const HISTORY_INDEX = "history_index";
    const HISTORY_TIME = "history_time";
    const HISTORY_USER_TOTAL = "history_user_total";
    const HISTORY_PRODUCT_TOTAL = "history_product_total";
    const HISTORY_PRODUCT_AVAILABLE = "history_product_available";
    const HISTORY_PRODUCT_RENT = "history_product_rent";
    const HISTORY_RENT_TOTAL = "history_rent_total";

    private $historyIndex;
    private $historyTime;
    private $historyUserTotal;
    private $historyProductTotal;
    private $historyProductAvailable;
    private $historyProductRent;
    private $historyRentTotal;

    public function __construct($params) {
        $this->historyIndex = $params[self::HISTORY_INDEX];
        $this->historyTime = $params[self::HISTORY_TIME];
        $this->historyUserTotal = $params[self::HISTORY_USER_TOTAL];
        $this->historyProductTotal = $params[self::HISTORY_PRODUCT_TOTAL];
        $this->historyProductAvailable = $params[self::HISTORY_PRODUCT_AVAILABLE];
        $this->historyProductRent = $params[self::HISTORY_PRODUCT_RENT];
        $this->historyRentTotal = $params[self::HISTORY_RENT_TOTAL];
    }

    /**
     * @return mixed
     */
    public function getHistoryIndex()
    {
        return $this->historyIndex;
    }

    /**
     * @param mixed $historyIndex
     */
    public function setHistoryIndex($historyIndex)
    {
        $this->historyIndex = $historyIndex;
    }

    /**
     * @return mixed
     */
    public function getHistoryTime()
    {
        return $this->historyTime;
    }

    /**
     * @param mixed $historyTime
     */
    public function setHistoryTime($historyTime)
    {
        $this->historyTime = $historyTime;
    }

    /**
     * @return mixed
     */
    public function getHistoryUserTotal()
    {
        return $this->historyUserTotal;
    }

    /**
     * @param mixed $historyUserTotal
     */
    public function setHistoryUserTotal($historyUserTotal)
    {
        $this->historyUserTotal = $historyUserTotal;
    }

    /**
     * @return mixed
     */
    public function getHistoryProductTotal()
    {
        return $this->historyProductTotal;
    }

    /**
     * @param mixed $historyProductTotal
     */
    public function setHistoryProductTotal($historyProductTotal)
    {
        $this->historyProductTotal = $historyProductTotal;
    }

    /**
     * @return mixed
     */
    public function getHistoryProductAvailable()
    {
        return $this->historyProductAvailable;
    }

    /**
     * @param mixed $historyProductAvailable
     */
    public function setHistoryProductAvailable($historyProductAvailable)
    {
        $this->historyProductAvailable = $historyProductAvailable;
    }

    /**
     * @return mixed
     */
    public function getHistoryProductRent()
    {
        return $this->historyProductRent;
    }

    /**
     * @param mixed $historyProductRent
     */
    public function setHistoryProductRent($historyProductRent)
    {
        $this->historyProductRent = $historyProductRent;
    }

    /**
     * @return mixed
     */
    public function getHistoryRentTotal()
    {
        return $this->historyRentTotal;
    }

    /**
     * @param mixed $historyRentTotal
     */
    public function setHistoryRentTotal($historyRentTotal)
    {
        $this->historyRentTotal = $historyRentTotal;
    }

}

?>