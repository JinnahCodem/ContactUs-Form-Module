<?php declare(strict_types=1);

/*
 * Copyright (c) 2022 ReCodem Pvt Ltd All rights reserved
 */

namespace Codem\Shoplist\Model;

use Codem\Shoplist\Api\Data\ShopInterface;
use Codem\Shoplist\Api\ShopRepositoryInterface;
use Codem\Shoplist\Model\Lists as ListsModel;
use Codem\Shoplist\Model\ResourceModel\Lists;
use Codem\Shoplist\Model\ResourceModel\Lists\CollectionFactory as ShopCollectionFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Magento\Store\Model\StoreManagerInterface;

/**
 * class ShopRepositoryModel for update,delete and save form data
 */
class ShopRepositoryModel implements ShopRepositoryInterface
{
    /**
     * @var ListsFactory
     */
    protected $listsfactory;
    /**
     * @var Lists
     */
    protected $listsResourceModelFactory;
    /**
     * @var ListsModel
     */
    protected $listsModel;

    /**
     * @var ShopCollectionFactory
     */
    protected $shopCollectionFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param ListsFactory $listsfactory
     * @param Lists $listsResourceModelFactory
     * @param \Codem\Shoplist\Model\Lists $listsModel
     * @param ShopCollectionFactory $shopCollectionFactory
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ListsFactory $listsfactory,
        Lists $listsResourceModelFactory,
        ListsModel $listsModel,
        ShopCollectionFactory $shopCollectionFactory,
        StoreManagerInterface $storeManager
    ) {
        $this->listsfactory = $listsfactory;
        $this->listsResourceModelFactory = $listsResourceModelFactory;
        $this->listsModel = $listsModel;
        $this->shopCollectionFactory = $shopCollectionFactory;
        $this->storeManager = $storeManager;
    }

    /**
     * Function to save shop list
     * @param ShopInterface $data
     * @return ShopInterface
     * @throws CouldNotSaveException
     */
    public function save(ShopInterface $data)
    {
        try {
            if (!is_string($data->getStoreView())) {
                $storeView = $data->getStoreView();
                $updatedStoreView = implode(",", $this->getStoreName($storeView));
                $data->setStoreView($updatedStoreView);
            }
            $this->listsResourceModelFactory->save($data);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(
                __('The Shop could not be saved'),
                $e->getMessage()
            );
        }
    }

    /**
     * Function to get shop using shopid
     * @param $shopId
     * @return ShopInterface|\Codem\Shoplist\Model\Lists|string
     * @throws NoSuchEntityException
     */
    public function getById($shopId)
    {
        $listObject = $this->listsfactory->create();
        if ($shopId) {
            try {
                $this->listsResourceModelFactory->load($listObject, $shopId, ShopInterface::SHOP_ID);
                if ($listObject->getShopId() === null) {
                    return "The shop does not exist";
                }
            } catch (\Exception $e) {
                throw new NoSuchEntityException(
                    __("The Shop that was requested doesn't exist. Verify and try again."),
                    $e->getMessage()
                );
            }
        }
        return $listObject;
    }

    /**
     * Function to delete shop using shop id
     * @param $shopId
     * @return string|void
     * @throws StateException
     */
    public function delete($shopId)
    {
        if ($shopId) {
            try {
                $listObject = $this->getById($shopId);
                if (is_string($listObject)) {
                    return "The shop does not exist";
                }
                $this->listsResourceModelFactory->delete($listObject);
                return "The Shop has been deleted Successfully";
            } catch (\Exception $e) {
                throw new StateException(
                    __('The shop couldn\'t be removed.'),
                    $e->getMessage()
                );
            }
        }
    }

    /**
     * Function to get shop list in WebApi
     * @return array|mixed|null
     */
    public function getList()
    {
        return $this->shopCollectionFactory->create()->getData();
    }

    /**
     * Function to update the shop in WebApi
     * @param ShopInterface $updateData
     * @return string
     * @throws CouldNotSaveException
     * @throws NoSuchEntityException
     */
    public function updateShop(ShopInterface $updateData)
    {
        $shopId = $updateData->getShopId();
        $shopName = $updateData->getShopName();
        $shopIdentifier = $updateData->getIdentifier();
        $shopStoreView = $updateData->getStoreView();
        $shopCountry = $updateData->getCountry();
        $shopImage = $updateData->getShopImage();

        $shopLists = $this->getById($shopId);
        if (is_string($shopLists)) {
            return "No Shops with the shop id : " . $shopId;
        }
        if ((sizeof($shopStoreView)) === 0) {
            return "Store View need not to be empty";
        }
        if ($shopLists->getShopId()) {
            if (isset($shopName)) {
                $shopLists->setShopName($shopName);
            }
            if (isset($shopIdentifier)) {
                $shopLists->setIdentifier($shopIdentifier);
            }
            if (isset($shopStoreView)) {
                $shopLists->setStoreView($shopStoreView);
            }
            if (isset($shopCountry)) {
                $shopLists->setCountry($shopCountry);
            }
            if (isset($shopImage)) {
                $shopLists->setShopImage($shopImage);
            }

            $this->save($shopLists);
            $message = "Shop has been updated successfully";
        }

        return $message;
    }

    /**
     * Function to get the store view name using store view code
     * @param $storeIds
     * @return array
     * @throws NoSuchEntityException
     */
    private function getStoreName($storeIds)
    {
        if (in_array("0", $storeIds)) {
            $storeName[] = "All Store Views";
        } else {
            foreach ($storeIds as $value) {
                $storeName[] = $this->storeManager->getStore($value)->getName();
            }
        }

        return $storeName;
    }
}
