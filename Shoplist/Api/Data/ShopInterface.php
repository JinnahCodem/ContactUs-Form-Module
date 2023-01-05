<?php declare(strict_types=1);

/*
 * Copyright (c) 2022 ReCodem Pvt Ltd All rights reserved
 */

namespace Codem\Shoplist\Api\Data;

/**
 * Interface for getting the ContactUs form data
 */
interface ShopInterface
{
    /**
     * Constants for keys of data array.
     */
    const SHOP_ID = 'shop_id';
    const NAME = 'shop_name';
    const IDENTIFIER = 'identifier';
    const STORE_VIEW = 'store_view';
    const COUNTRY = 'country';
    const SHOP_IMAGE = 'shop_image';
    const BASE_IMG_TMP_PATH = 'shopListImage/tmp/image';
    const BASE_IMG_PATH = 'shopListImage/image';
    const ALLOWED_IMG_EXTENSIONS = ['jpg', 'jpeg', 'gif', 'png'];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * @return int
     */
    public function getShopId();

    /**
     * @param $shopId int
     * @return $this
     */
    public function setShopId($shopId);

    /**
     * @return string
     */
    public function getShopName();

    /**
     * Set Name
     * @param $name string
     * @return $this
     */
    public function setShopName($name);

    /**
     * @return string
     */
    public function getIdentifier();

    /**
     * Set Identifier
     * @param $identifier string
     * @return $this
     */
    public function setIdentifier($identifier);

    /**
     * @return mixed
     */
    public function getStoreView();

    /**
     * Set StoreView
     * @param $storeView mixed
     * @return $this
     */
    public function setStoreView($storeView);

    /**
     * @return string
     */
    public function getCountry();

    /**
     * Set Country
     * @param $country string
     * @return $this
     */
    public function setCountry($country);

    /**
     * @return string/null
     */
    public function getShopImage();

    /**
     * Set Image
     * @param $image string
     * @return $this
     */
    public function setShopImage($image);

}
