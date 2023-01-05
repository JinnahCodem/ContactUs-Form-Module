<?php declare(strict_types=1);

/*
 * Copyright (c) 2023 ReCodem Pvt Ltd All rights reserved
 */

namespace Codem\Shoplist\Model\ResourceModel\Lists;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection to get resource model collection
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'shop_id';
    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'Codem_shoplist_lists_collection';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'lists_collection';
    /**
     * Defining resource model.
     */
    protected function _construct()
    {
        $this->_init('Codem\Shoplist\Model\Lists', 'Codem\Shoplist\Model\ResourceModel\Lists');
    }
}
