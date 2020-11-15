<?php

class ProductGroup {

    const PRODUCT_GROUP_INDEX = "product_group_index";
    const PRODUCT_GROUP_NAME = "product_group_name";
    const PRODUCT_GROUP_RENTABLE = "product_group_rentable";
    const PRODUCT_GROUP_PRIORITY = "product_group_priority";

    private $productGroupIndex;
    private $productGroupName;
    private $productGroupRentable;
    private $productGroupPriority;

    public function __construct($params) {
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