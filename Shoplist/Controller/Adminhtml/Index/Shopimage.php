<?php declare(strict_types=1);

/*
 * Copyright (c) 2022 ReCodem Pvt Ltd All rights reserved
 */

namespace Codem\Shoplist\Controller\Adminhtml\Index;

use Codem\Shoplist\Model\ImageUploader;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * Class Shopimage for uploading shop image
 */
class Shopimage extends Action
{
    /**
     * @var ImageUploader
     */
    public $imageUploader;

    /**
     * Shopimage constructor.
     * @param Context $context
     * @param ImageUploader $imageUploader
     */
    public function __construct(
        Context $context,
        ImageUploader $imageUploader
    ) {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
    }

    /**
     * @return ResponseInterface|ResultInterface
     */
    public function execute()
    {
        try {
            $imageUploadId = $this->getRequest()->getParam('param_name');
            $result = $this->imageUploader->saveFileToTmpDir($imageUploadId);
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
