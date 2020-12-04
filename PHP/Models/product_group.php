<?php

class ProductGroup {

    const PRODUCT_GROUP = "productgroup";
    const PRODUCT_GROUP_INDEX = "product_group_index";
    const PRODUCT_GROUP_NAME = "product_group_name";
    const PRODUCT_GROUP_RENTABLE = "product_group_rentable";
    const PRODUCT_GROUP_PRIORITY = "product_group_priority";
    const GROUPS = "groups";
    const GROUP_INDEX = "group_index";
    const GROUP_NAME = "group_name";
    const GROUP_RENTABLE = "group_rentable";
    const GROUP_PRIORITY = "group_priority";
    const GROUP_COUNT_AVAILABLE = "group_count_available";
    const GROUP_COUNT_UNAVAILABLE = "group_count_unavailable";
    const GROUP_COUNT_BROKEN = "group_count_broken";
    const GROUP_COUNT_REPAIR = "group_count_repair";
    const GROUP_COUNT_RENT = "group_count_rent";

    private $productGroupIndex;
    private $productGroupName;
    private $productGroupRentable;
    private $productGroupPriority;

    public function __construct($params = null) {
        if (!isset($params)) return;
        $this->productGroupIndex = $params[self::PRODUCT_GROUP_INDEX];
        $this->productGroupName = $params[self::PRODUCT_GROUP_NAME];
        $this->productGroupRentable = $params[self::PRODUCT_GROUP_RENTABLE];
        $this->productGroupPriority = $params[self::PRODUCT_GROUP_PRIORITY];
    }

    /**
     * @return mixed
     */
    public function getProductGroupIndex()
    {
        return $this->productGroupIndex;
    }

    /**
     * @param mixed $productGroupIndex
     */
    public function setProductGroupIndex($productGroupIndex)
    {
        $this->productGroupIndex = $productGroupIndex;
    }

    /**
     * @return mixed
     */
    public function getProductGroupName()
    {
        return $this->productGroupName;
    }

    /**
     * @param mixed $productGroupName
     */
    public function setProductGroupName($productGroupName)
    {
        $this->productGroupName = $productGroupName;
    }

    /**
     * @return mixed
     */
    public function getProductGroupRentable()
    {
        return $this->productGroupRentable;
    }

    /**
     * @param mixed $productGroupRentable
     */
    public function setProductGroupRentable($productGroupRentable)
    {
        $this->productGroupRentable = $productGroupRentable;
    }

    /**
     * @return mixed
     */
    public function getProductGroupPriority()
    {
        return $this->productGroupPriority;
    }

    /**
     * @param mixed $productGroupPriority
     */
    public function setProductGroupPriority($productGroupPriority)
    {
        $this->productGroupPriority = $productGroupPriority;
    }

}

?>