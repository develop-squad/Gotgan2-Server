<?php

class Rent {

    const RENT = "rents";
    const RENT_INDEX = "rent_index";
    const RENT_USER = "rent_user";
    const RENT_PRODUCT = "rent_product";
    const RENT_STATUS = "rent_status";
    const RENT_TIME_START = "rent_time_start";
    const RENT_TIME_END = "rent_time_end";
    const RENT_TIME_RETURN = "rent_time_return";
    const RENT_DELAYED = "rent_delayed";
    const RENT_USER_INDEX = "rent_user_index";
    const RENT_USER_NAME = "rent_user_name";
    const RENT_USER_EMAIL = "rent_user_email";
    const RENT_USER_UUID = "rent_user_uuid";
    const RENT_USER_ID = "rent_user_id";
    const RENT_PRODUCT_INDEX = "rent_product_index";
    const RENT_PRODUCT_GROUP_INDEX = "rent_product_group_index";
    const RENT_PRODUCT_GROUP_NAME = "rent_product_group_name";
    const RENT_PRODUCT_NAME = "rent_product_name";
    const RENT_PRODUCT_BARCODE = "rent_product_barcode";

    const RENT_MESSAGE_DUE_TITLE = "대여 반납일 알림";
    const RENT_MESSAGE_DUE_CONTENT = "오늘은 대여하신 물품의 반납일입니다";
    const RENT_MESSAGE_LATE_TITLE = "대여 미반납 알림";
    const RENT_MESSAGE_LATE_CONTENT = "대여하신 물품이 반납일이 지났습니다";

    private $rentIndex;
    private $rentUser;
    private $rentProduct;
    private $rentStatus;
    private $rentTimeStart;
    private $rentTimeEnd;
    private $rentTimeReturn;

    public function __construct($params = null) {
        if (!isset($params)) return;
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