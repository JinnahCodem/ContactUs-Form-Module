<?php
declare(strict_types=1);

/*
 * Copyright (c) 2022 ReCodem Pvt Ltd All rights reserved
 */

namespace Codem\ContactUs\Controller\Adminhtml\Index;

use Codem\ContactUs\Api\ContactRepositoryInterface;
use Codem\ContactUs\Model\ContactsFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\File\UploaderFactory;
use Magento\Framework\Filesystem;
use Magento\Framework\Image\AdapterFactory;

/**
 * Class of functions to save the add new and edited contactus data
 */
class Save extends Action
{
    /**
     * @var AdapterFactory
     */
    protected $adapterFactory;

    /**
     * @var UploaderFactory
     */
    protected $uploader;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var ContactsFactory
     */
    protected $contactsFactory;

    /**
     * @var ContactRepositoryInterface
     */
    protected $contactRepository;

    /**
     * @param Context $context
     * @param AdapterFactory $adapterFactory
     * @param Filesystem $filesystem
     * @param ContactsFactory $contactsFactory
     * @param ContactRepositoryInterface $contactRepository
     */
    public function __construct(
        Context $context,
        AdapterFactory $adapterFactory,
        Filesystem $filesystem,
        ContactsFactory $contactsFactory,
        ContactRepositoryInterface $contactRepository
    ) {
        $this->adapterFactory = $adapterFactory;
        $this->filesystem = $filesystem;
        $this->contactsFactory = $contactsFactory;
        $this->contactRepository = $contactRepository;

        parent::__construct($context);
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        if (!$data) {
            $this->_redirect('contactusbackend/index/addnew');
            return;
        }
        try {
            $rowData = $this->contactsFactory->create();
            $rowData->setData($data['contacts']);
            $this->contactRepository->save($rowData);

            $this->messageManager->addSuccessMessage(__('Contactus data has been saved successfully.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__($e->getMessage()));
        }
        $this->_redirect('contactusbackend/index/index');
    }
}
