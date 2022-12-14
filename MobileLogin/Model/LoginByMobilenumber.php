<?php declare(strict_types=1);

/*
 * Copyright (c) 2022 ReCodem Pvt Ltd All rights reserved
 */

namespace Codem\MobileLogin\Model;

use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class of function to match the phone number in the database and return user email id
 */
class LoginByMobilenumber
{

    /**
     * @var CollectionFactory
     */
    protected $customerCollecctionFactory;

    /**
     * @param CollectionFactory $customerCollecctionFactory
     */
    public function __construct(CollectionFactory $customerCollecctionFactory)
    {
        $this->customerCollecctionFactory = $customerCollecctionFactory;
    }

    /**
     * @param $mobileNumber
     * @param $password
     * @return array|false|mixed|null
     * @throws LocalizedException
     */
    public function authenticateByMobilenumber($mobileNumber, $password)
    {
        $customer = $this->customerCollecctionFactory->create()
                            ->addAttributeToSelect('*')
                            ->addAttributeToFilter('mobile_number', ['eq' => $mobileNumber ])
                            ->getFirstItem();

        if ($customer) {
            return $customer->getData('email');
        }
        return false;
    }
}
