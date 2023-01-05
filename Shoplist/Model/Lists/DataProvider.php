<?php declare(strict_types=1);

/*
 * Copyright (c) 2022 ReCodem Pvt Ltd All rights reserved
 */

namespace Codem\Shoplist\Model\Lists;

use Codem\Shoplist\Model\Lists;
use Codem\Shoplist\Model\ResourceModel\Lists\CollectionFactory;
use Codem\Shoplist\Ui\Component\Listing\Column\Image;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

/**
 * Class DataProvider to provide resource collection data to the form fields
 * @package Codem\Shoplist\Model\Lists
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $shopCollectionFactory
     * @param StoreManagerInterface $storeManager
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $shopCollectionFactory,
        StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $shopCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->_storeManager = $storeManager;
    }

    /**
     * @return array
     * @throws NoSuchEntityException
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();
        $this->loadedData = [];
        /** @var Lists $lists */
        foreach ($items as $lists) {
            $this->loadedData[$lists->getShopId()]['lists'] = $lists->getData();
            if ($lists->getShopImage()) {
                $m[0]['name'] = $lists->getShopImage();
                $m[0]['url'] = $this->getMediaUrl() . $lists->getShopImage();
                $this->loadedData[$lists->getShopId()]['lists']['shop_image'] = $m;
            }
        }

        return $this->loadedData;
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     */
    public function getMediaUrl()
    {
        return $this->_storeManager->getStore()
                ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . Image::IMAGE_PATH;

    }
}
