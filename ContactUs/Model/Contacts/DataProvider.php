<?php
declare(strict_types=1);

/*
 * Copyright (c) 2022 ReCodem Pvt Ltd All rights reserved
 */

namespace Codem\ContactUs\Model\Contacts;

use Codem\ContactUs\Model\Contacts;
use Codem\ContactUs\Model\ResourceModel\Contacts\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;

/**
 * Class DataProvider to provide resource collection data to the form fields
 * @package Codem\ContactUs\Model\Contacts
 */
class DataProvider extends AbstractDataProvider
{

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $contactCollectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $contactCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $contactCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();

        $this->loadedData = [];
        /** @var Contacts $contacts */
        foreach ($items as $contacts) {

            $this->loadedData[$contacts->getEntityId()]['contacts'] = $contacts->getData();
        }

        return $this->loadedData;
    }
}
