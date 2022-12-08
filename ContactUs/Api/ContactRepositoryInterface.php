<?php
declare(strict_types=1);

/*
 * Copyright (c) 2022 ReCodem Pvt Ltd All rights reserved
 */

namespace Codem\ContactUs\Api;

use Codem\ContactUs\Api\Data\ContactInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;

/**
 * Interface for update/delete and save ContactUs form data
 */
interface ContactRepositoryInterface
{
    /**
     * Create or update ContactUs Data
     * @param ContactInterface $data
     * @return ContactInterface
     * @throws CouldNotSaveException
     */
    public function save(ContactInterface $data);

    /**
     * Get info about ContactUs Data by Entity id
     *
     * @param int $entityId
     * @return ContactInterface
     * @throws NoSuchEntityException
     */
    public function getById($entityId);

    /**
     * Delete ContactUs Data
     * @param int $entityId
     * @return bool Will returned True if deleted
     * @throws StateException
     */
    public function delete($entityId);

}
