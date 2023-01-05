<?php declare(strict_types=1);

/*
 * Copyright (c) 2022 ReCodem Pvt Ltd All rights reserved
 */

namespace Codem\Shoplist\Model;

use Codem\Shoplist\Api\Data\ShopInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class Lists to initialize resource model objects
 */
class Lists extends AbstractModel implements ShopInterface
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Codem\Shoplist\Model\ResourceModel\Lists');
    }

    /**
     * @return int
     */
    public function getShopId()
    {
        return $this->getdata(self::SHOP_ID);
    }

    /**
     * Set Id
     */
    public function setShopId($shopId)
    {
        return $this->setData(self::SHOP_ID, $shopId);
    }

    /**
     * @return string
     */
    public function getShopName()
    {
        return $this->getdata(self::NAME);
    }

    /**
     * Set Shop Name
     */
    public function setShopName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->getData(self::IDENTIFIER);
    }

    /**
     * Set Shop Identifier
     */
    public function setIdentifier($identifier)
    {
        return $this->setData(self::IDENTIFIER, $identifier);
    }

    /**
     * @return mixed
     */
    public function getStoreView()
    {
        return $this->getData(self::STORE_VIEW);
    }

    /**
     * Set Shop Store View
     */
    public function setStoreView($storeView)
    {
        return $this->setData(self::STORE_VIEW, $storeView);
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->getData(self::COUNTRY);
    }

    /**
     * Set Country
     */
    public function setCountry($country)
    {
        return $this->setData(self::COUNTRY, $country);
    }

    /**
     * @return string/null
     */
    public function getShopImage()
    {
        return $this->getData(self::SHOP_IMAGE);
    }

    /**
     * Set Image
     */
    public function setShopImage($image)
    {
        return $this->setData(self::SHOP_IMAGE, $image);
    }

}
