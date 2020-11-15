<?php

class Rent {

    const RENT_INDEX = "rent_index";
    const RENT_USER = "rent_user";
    const RENT_PRODUCT = "rent_product";
    const RENT_STATUS = "rent_status";
    const RENT_TIME_START = "rent_time_start";
    const RENT_TIME_END = "rent_time_end";
    const RENT_TIME_RETURN = "rent_time_return";

    private $rentIndex;
    private $rentUser;
    private $rentProduct;
    private $rentStatus;
    private $rentTimeStart;
    private $rentTimeEnd;
    private $rentTimeReturn;

    public function __construct($params) {
        $this->rentIndex = $params[self::RENT_INDEX];
        $this->rentUser = $params[self::RENT_USER];
        $this->rentProduct = $params[self::RENT_PRODUCT];
        $this->rentStatus = $params[self::RENT_STATUS];
        $this->rentTimeStart = $params[self::RENT_TIME_START];
        $this->rentTimeEnd = $params[self::RENT_TIME_END];
        $this->rentTimeReturn = $params[self::RENT_TIME_RETURN];
    }

    /**
     * @return mixed
     */
    public function getRentIndex()
    {
        return $this->rentIndex;
    }

    /**
     * @param mixed $rentIndex
     */
    public function setRentIndex($rentIndex)
    {
        $this->rentIndex = $rentIndex;
    }

    /**
     * @return mixed
     */
    public function getRentUser()
    {
        return $this->rentUser;
    }

    /**
     * @param mixed $rentUser
     */
    public function setRentUser($rentUser)
    {
        $this->rentUser = $rentUser;
    }

    /**
     * @return mixed
     */
    public function getRentProduct()
    {
        return $this->rentProduct;
    }

    /**
     * @param mixed $rentProduct
     */
    public function setRentProduct($rentProduct)
    {
        $this->rentProduct = $rentProduct;
    }

    /**
     * @return mixed
     */
    public function getRentStatus()
    {
        return $this->rentStatus;
    }

    /**
     * @param mixed $rentStatus
     */
    public function setRentStatus($rentStatus)
    {
        $this->rentStatus = $rentStatus;
    }

    /**
     * @return mixed
     */
    public function getRentTimeStart()
    {
        return $this->rentTimeStart;
    }

    /**
     * @param mixed $rentTimeStart
     */
    public function setRentTimeStart($rentTimeStart)
    {
        $this->rentTimeStart = $rentTimeStart;
    }

    /**
     * @return mixed
     */
    public function getRentTimeEnd()
    {
        return $this->rentTimeEnd;
    }

    /**
     * @param mixed $rentTimeEnd
     */
    public function setRentTimeEnd($rentTimeEnd)
    {
        $this->rentTimeEnd = $rentTimeEnd;
    }

    /**
     * @return mixed
     */
    public function getRentTimeReturn()
    {
        return $this->rentTimeReturn;
    }

    /**
     * @param mixed $rentTimeReturn
     */
    public function setRentTimeReturn($rentTimeReturn)
    {
        $this->rentTimeReturn = $rentTimeReturn;
    }

}

?>