<?php

class Product {

    const PRODUCT_INDEX = "productIndex";
    const PRODUCT_GROUP = "productGroup";
    const PRODUCT_NAME = "productName";
    const PRODUCT_STATUS = "productStatus";
    const PRODUCT_OWNER = "productOwner";
    const PRODUCT_RENT = "productRent";
    const PRODUCT_BARCODE = "productBarcode";
    const PRODUCT_CREATED = "productCreated";

    private $productIndex;
    private $productGroup;
    private $productName;
    private $productStatus;
    private $productOwner;
    private $productRent;
    private $productBarcode;
    private $productCreated;

    public function __construct($params) {
        $this->productIndex = $params[self::PRODUCT_INDEX];
        $this->productGroup = $params[self::PRODUCT_GROUP];
        $this->productName = $params[self::PRODUCT_NAME];
        $this->productStatus = $params[self::PRODUCT_STATUS];
        $this->productOwner = $params[self::PRODUCT_OWNER];
        $this->productRent = $params[self::PRODUCT_RENT];
        $this->productBarcode = $params[self::PRODUCT_BARCODE];
        $this->productCreated = $params[self::PRODUCT_CREATED];
    }

    /**
     * @return int product Index
     */
    public function getProductIndex()
    {
        return $this->productIndex;
    }

    /**
     * @param int $productIndex
     */
    public function setProductIndex($productIndex)
    {
        $this->productIndex = $productIndex;
    }

    /**
     * @return int product Group
     */
    public function getProductGroup()
    {
        return $this->productGroup;
    }

    /**
     * @param int $productGroup
     */
    public function setProductGroup($productGroup)
    {
        $this->productGroup = $productGroup;
    }

    /**
     * @return mixed
     */
    public function getProductName()
    {
        return $this->productName;
    }

    /**
     * @param mixed $productName
     */
    public function setProductName($productName)
    {
        $this->productName = $productName;
    }

    /**
     * @return mixed
     */
    public function getProductStatus()
    {
        return $this->productStatus;
    }

    /**
     * @param mixed $productStatus
     */
    public function setProductStatus($productStatus)
    {
        $this->productStatus = $productStatus;
    }

    /**
     * @return mixed
     */
    public function getProductOwner()
    {
        return $this->productOwner;
    }

    /**
     * @param mixed $productOwner
     */
    public function setProductOwner($productOwner)
    {
        $this->productOwner = $productOwner;
    }

    /**
     * @return mixed
     */
    public function getProductRent()
    {
        return $this->productRent;
    }

    /**
     * @param mixed $productRent
     */
    public function setProductRent($productRent)
    {
        $this->productRent = $productRent;
    }

    /**
     * @return mixed
     */
    public function getProductBarcode()
    {
        return $this->productBarcode;
    }

    /**
     * @param int $productBarcode
     */
    public function setProductBarcode($productBarcode)
    {
        $this->productBarcode = $productBarcode;
    }

    /**
     * @return mixed
     */
    public function getProductCreated()
    {
        return $this->productCreated;
    }

    /**
     * @param mixed $productCreated
     */
    public function setProductCreated($productCreated)
    {
        $this->productCreated = $productCreated;
    }

}

?>