<?php declare(strict_types=1);

/*
 * Copyright (c) 2022 ReCodem Pvt Ltd All rights reserved
 */

namespace Codem\Shoplist\Controller\Adminhtml\Index;

use Codem\Shoplist\Api\ShopRepositoryInterface;
use Codem\Shoplist\Model\ListsFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Edit for edit page
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
     * @var ListsFactory
     */
    protected $listsFactory;

    /**
     * @var ShopRepositoryInterface
     */
    protected $shopRepository;

    /**
     * @param PageFactory $resultPageFactory
     * @param ListsFactory $listsFactory
     * @param ShopRepositoryInterface $shopRepository
     * @param Context $context
     */
    public function __construct(
        PageFactory $resultPageFactory,
        ListsFactory $listsFactory,
        ShopRepositoryInterface $shopRepository,
        Context $context
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->listsFactory = $listsFactory;
        $this->shopRepository = $shopRepository;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page|ResponseInterface|Redirect|ResultInterface|Page
     * @throws NoSuchEntityException
     */
    public function execute()
    {
        $shopid = $this->getRequest()->getParam('id');
        if ($shopid) {
            $lists = $this->shopRepository->getById($shopid);

            if (!$lists->getShopId()) {
                $this->messageManager->addErrorMessage(__('Shop no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->setPath(
                    'shoplist/*/*'
                );

                return $resultRedirect;
            }
        } else {
            $lists = $this->listsFactory->create();
        }
        /** @var \Magento\Backend\Model\View\Result\Page|Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Codem_Shoplist::submenu');
        $resultPage->getConfig()->getTitle()
            ->set(__('Shops Set'))
            ->prepend($lists->getShopId() ? $lists->getShopName() : __('New Shop'));

        return $resultPage;
    }
}
