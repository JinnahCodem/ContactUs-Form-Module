<?php declare(strict_types=1);

/*
 * Copyright (c) 2022 ReCodem Pvt Ltd All rights reserved
 */

namespace Codem\Shoplist\Controller\Adminhtml\Index;

use Codem\Shoplist\Api\ShopRepositoryInterface;
use Codem\Shoplist\Model\ResourceModel\Lists\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;

/**
 * Class MassDelete for selecting grid items to delete in bulk
 */
class MassDelete extends Action
{
    /**
     * Massactions filter.
     *
     * @var Filter
     */
    protected $_filter;

    /**
     * @var CollectionFactory
     */
    protected $_collectionFactory;
    /**
     * @var ShopRepositoryInterface
     */
    protected $shopRepository;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param ShopRepositoryInterface $shopRepository
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        ShopRepositoryInterface $shopRepository
    ) {
        $this->_filter = $filter;
        $this->_collectionFactory = $collectionFactory;
        $this->shopRepository = $shopRepository;
        parent::__construct($context);
    }

    /**
     * Function to delete action
     * @return Redirect
     */
    public function execute()
    {
        try {
            $collection = $this->_filter->getCollection($this->_collectionFactory->create());
            $collectionSize = $collection->getSize();
            foreach ($collection->getItems() as $item) {
                $shop = $this->shopRepository->delete($item->getShopId());
            }
            $this->messageManager->addSuccessMessage(
                __('A total of %1 shop(s) have been deleted.', $collectionSize)
            );
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                __('Cannot delete the shop', $e->getMessage())
            );
        }
        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/index');
    }
}
