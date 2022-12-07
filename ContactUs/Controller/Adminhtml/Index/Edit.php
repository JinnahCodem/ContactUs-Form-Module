<?php
declare(strict_types=1);

namespace Codem\ContactUs\Controller\Adminhtml\Index;

use Codem\ContactUs\Api\ContactRepositoryInterface;
use Codem\ContactUs\Model\ContactsFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class of functions to Edit the page
 */
class Edit extends Action
{

    /**
     * Page factory
     *
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var ContactsFactory
     */
    protected $contactsFactory;
    /**
     * @var ContactRepositoryInterface
     */
    protected $contactRepository;

    /**
     * Edit constructor
     *
     * @param PageFactory $resultPageFactory
     * @param ContactsFactory $contactsFactory
     * @param ContactRepositoryInterface $contactRepository
     * @param Context $context
     */
    public function __construct(
        PageFactory $resultPageFactory,
        ContactsFactory $contactsFactory,
        ContactRepositoryInterface $contactRepository,
        Context $context
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->contactsFactory = $contactsFactory;
        $this->contactRepository = $contactRepository;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|ResultInterface|void
     * @throws NoSuchEntityException
     */
    public function execute()
    {
        $entityId = $this->getRequest()->getParam('id');
        if ($entityId) {
            $contacts = $this->contactRepository->getById($entityId);

            if (!$contacts->getEntityId()) {
                $this->messageManager->addErrorMessage(__('ContactUS Data no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->setPath(
                    'contactusbackend/index/index'
                );

                return $resultRedirect;
            }
        } else {
            $contacts = $this->contactsFactory->create();
        }
        /** @var \Magento\Backend\Model\View\Result\Page|Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Codem_ContactUs::menu');
        $resultPage->getConfig()->getTitle()
            ->set(__('ContactUs Data'))
            ->prepend($contacts->getEntityId() ? $contacts->getName() : __('Add New Data'));

        return $resultPage;
    }
}
