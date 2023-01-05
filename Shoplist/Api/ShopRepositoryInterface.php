<?php declare(strict_types=1);

/*
 * Copyright (c) 2022 ReCodem Pvt Ltd All rights reserved
 */

namespace Codem\Shoplist\Api;

use Codem\Shoplist\Api\Data\ShopInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;

/**
 * Interface for get,update and delete shoplist
 */
interface ShopRepositoryInterface
{
    /**
     * Create or update shop
     * @param ShopInterface $data
     * @return ShopInterface
     * @throws CouldNotSaveException
     */
    public function save(ShopInterface $data);

    /**
     * Get info about shop by shop id
     *
     * @param int $shopId
     * @return ShopInterface
     * @throws NoSuchEntityException
     */
    public function getById($shopId);

    /**
     * Delete shop
     *
     * @param int $shopId
     * @return bool Will returned True if deleted
     * @throws StateException
     */

    public function delete($shopId);

    /**
     * @return mixed
     */
    public function getList();

    /**
     * @param ShopInterface $updateData
     * @return mixed
     *
     */
    public function updateShop(ShopInterface $updateData);
}
