<?php declare(strict_types=1);

/**
 * Copyright (c) 2022 ReCodem Pvt Ltd All rights reserved
 */

namespace Codem\MobileLogin\Plugin\Model\ResourceModel\Customer;

use Magento\Customer\Model\Customer;
use Magento\Customer\Model\ResourceModel\Customer as ResourceModel;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory as CustomerCollectionFactory;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class ValidateUniquePhonenumber to validates, if the customer's phone number is unique
 */
class ValidateUniquePhonenumber
{

    /**
     * @var CustomerCollectionFactory
     */
    private $customerCollectionFactory;

    /**
     * @param CustomerCollectionFactory $customerCollectionFactory
     */
    public function __construct(
        CustomerCollectionFactory $customerCollectionFactory
    ) {
        $this->customerCollectionFactory = $customerCollectionFactory;
    }

    /**
     * Validates if the customer phone number is unique
     *
     * @param ResourceModel $subject
     * @param Customer $customer
     *
     * @throws LocalizedException
     */
    public function beforeSave(ResourceModel $subject, Customer $customer)
    {
        $collection = $this->customerCollectionFactory->create()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('mobile_number', ['eq' => $customer->getData('mobile_number') ]);

        if ($customer->getId()) {
            $collection->addAttribuTeToFilter(
                'entity_id',
                [
                    'neq' => (int) $customer->getId(),
                ]
            );
        }

        if ($collection->getSize() > 0) {
            throw new LocalizedException(
                __('A customer with the same phone number already exists in an associated website.')
            );
        }
    }
}
