<?php declare(strict_types=1);

/*
 * Copyright (c) 2023 ReCodem Pvt Ltd All rights reserved
 */

namespace Codem\Shoplist\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Stdlib\DateTime\DateTime;

/**
 * Class Lists resource-model class for creating and updating data
 */
class Lists extends AbstractDb
{
    /**
     * @var DateTime
     */
    protected $date;

    /**
     * Lists constructor.
     * @param Context $context
     * @param DateTime $date
     */
    public function __construct(
        Context $context,
        DateTime $date
    ) {
        parent::__construct($context);
        $this->date = $date;
    }

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('shoplist', 'shop_id');
    }

    /**
     * @param AbstractModel $object
     *
     * @return $this|AbstractDb
     */
    protected function _beforeSave(AbstractModel $object)
    {
        //set default Update At and Create At time post
        $object->setUpdatedAt($this->date->date());
        if ($object->isObjectNew()) {
            $object->setCreatedAt($this->date->date());
        }

        return $this;
    }
}
