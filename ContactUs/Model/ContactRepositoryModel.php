<?php

declare(strict_types=1);

/*
 * Copyright (c) 2022 ReCodem Pvt Ltd All rights reserved
 */

namespace Codem\ContactUs\Model;

use Codem\ContactUs\Api\ContactRepositoryInterface;
use Codem\ContactUs\Api\Data\ContactInterface;
use Codem\ContactUs\Model\Contacts as ContactsModel;
use Codem\ContactUs\Model\ResourceModel\Contacts;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Codem\ContactUs\Model\ContactsFactory;

/**
 * class ContactRepositoryModel for update,delete and save form data
 */
class ContactRepositoryModel implements ContactRepositoryInterface
{

    /**
     * @var ContactsFactory
     */
    protected $contactsfactory;
    /**
     * @var Contacts
     */
    protected $contactsResourceModelFactory;
    /**
     * @var ContactsModel
     */
    protected $contactsModel;

    public function __construct(
        ContactsFactory $contactsfactory,
        Contacts             $contactsResourceModelFactory,
        ContactsModel        $contactsModel
    ) {
        $this->contactsfactory = $contactsfactory;
        $this->contactsResourceModelFactory = $contactsResourceModelFactory;
        $this->contactsModel = $contactsModel;
    }


    /**
     * @param ContactInterface $data
     * @return ContactInterface|void
     * @throws CouldNotSaveException
     */
    public function save(ContactInterface $data)
    {
        try {
            $this->contactsResourceModelFactory->save($data);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(
                __('The Contacts could not be saved'),
                $e->getMessage()
            );
        }
    }

    /**
     * @param $entityId
     * @return ContactInterface
     * @throws NoSuchEntityException
     */
    public function getById($entityId)
    {
        $contactObject = $this->contactsfactory->create();
        if ($entityId) {
            try {
                $this->contactsResourceModelFactory->load($contactObject, $entityId, ContactInterface::ENTITY_ID);
            } catch (\Exception $e) {
                throw new NoSuchEntityException(
                    __("The Contact requested doesn't exist. Verify and try again."),
                    $e->getMessage()
                );
            }
        }
        return $contactObject;
    }

    /**
     * @param $entityId
     * @return bool|Contacts|void
     */
    public function delete($entityId)
    {
        if ($entityId) {
            try {
                $contactObject = $this->getById($entityId);
                return $this->contactsResourceModelFactory->delete($contactObject);
            } catch (\Exception $e) {
                throw new StateException(
                    __('The Contact couldn\'t be removed.'),
                    $e->getMessage()
                );
            }
        }
    }
}
