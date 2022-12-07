<?php

declare(strict_types=1);

namespace Codem\ContactUs\Controller\Adminhtml\Index;

use Codem\ContactUs\Api\ContactRepositoryInterface;
use Codem\ContactUs\Model\ResourceModel\Contacts\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Ui\Component\MassAction\Filter;

/**
 * Class MassDelete to delete the selected ContactUs data in bulk
 */
class MassDelete extends Action
{
    /**
     * Mass Action Filter
     *
     * @var Filter
     */
    protected $_filter;

    /**
     * @var CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @var ContactRepositoryInterface
     */
    protected $contactRepository;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param ContactRepositoryInterface $contactRepository
     */
    public function __construct(Context $context, Filter $filter, CollectionFactory $collectionFactory, ContactRepositoryInterface $contactRepository)
    {
        $this->_filter = $filter;
        $this->_collectionFactory = $collectionFactory;
        $this->contactRepository = $contactRepository;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|Redirect|Redirect&ResultInterface|ResultInterface
     */
    public function execute()
    {
        try {
            $collection = $this->_filter->getCollection($this->_collectionFactory->create());
            $collectionSize = $collection->getSize();
            foreach ($collection->getItems() as $item) {
                $shop = $this->contactRepository->delete($item->getEntityID());
            }
            $this->messageManager->addSuccessMessage(
                __('Selected %1 contactus detail(s) have been deleted.', $collectionSize)
            );
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                __('Cannot delete the selected contactus details', $e->getMessage())
            );
        }
        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/index');
    }
}
