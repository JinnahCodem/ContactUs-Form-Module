<?php
declare(strict_types=1);

/*
 * Copyright (c) 2022 ReCodem Pvt Ltd All rights reserved
 */

namespace Codem\ContactUs\Model\ResourceModel\Contacts;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection to get resource model collection
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'Codem_contactUs_contacts_collection';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'contacts_collection';

    /**
     * Defining Resource Model
     */
    public function _construct()
    {
        $this->_init('Codem\ContactUs\Model\Contacts', 'Codem\ContactUs\Model\ResourceModel\Contacts');
    }
}
