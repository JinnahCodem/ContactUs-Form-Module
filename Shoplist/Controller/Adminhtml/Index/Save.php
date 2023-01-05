<?php declare(strict_types=1);

/*
 * Copyright (c) 2022 ReCodem Pvt Ltd All rights reserved
 */

namespace Codem\Shoplist\Controller\Adminhtml\Index;

use Codem\Shoplist\Api\ShopRepositoryInterface;
use Codem\Shoplist\Model\ImageUploader;
use Codem\Shoplist\Model\ListsFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Filesystem;
use Magento\Framework\Image\AdapterFactory;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Save for submitting the form data
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
     * @var ListsFactory
     */
    protected $listsFactory;

    /**
     * @var ShopRepositoryInterface
     */
    protected $shoprepository;

    /**
     * @var ImageUploader
     */
    protected $imageUploader;

    /**
     * @var StoreManagerInterface $storeManager
     */
    protected $storeManager;

    /**
     * @param Context $context
     * @param AdapterFactory $adapterFactory
     * @param UploaderFactory $uploader
     * @param Filesystem $filesystem
     * @param ListsFactory $listsFactory
     * @param ShopRepositoryInterface $shoprepository
     * @param ImageUploader $imageUploader
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        AdapterFactory $adapterFactory,
        UploaderFactory $uploader,
        Filesystem $filesystem,
        ListsFactory $listsFactory,
        ShopRepositoryInterface  $shoprepository,
        ImageUploader $imageUploader,
        StoreManagerInterface $storeManager
    ) {
        $this->adapterFactory = $adapterFactory;
        $this->uploader = $uploader;
        $this->filesystem = $filesystem;
        $this->listsFactory = $listsFactory;
        $this->shoprepository = $shoprepository;
        $this->imageUploader = $imageUploader;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * Create or update the shop data
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();

        if (!$data) {
            $this->_redirect('shoplist/index/add');
            return;
        }
        try {
            $rowData = $this->listsFactory->create();
            $data = $this->addimagepath($data);
            $rowData->setData($data['lists']);
            $this->shoprepository->save($rowData);

            $this->messageManager->addSuccessMessage(__('Shop has been successfully saved.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__($e->getMessage()));
        }
        $this->_redirect('shoplist');
    }


    /**
     * Get uploaded image and move to base image path, add set shop image
     * @param $datalist
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addimagepath($datalist)
    {
        $data = $datalist['lists'];

        if (isset($data['shop_image'][0]['name']) && isset($data['shop_image'][0]['tmp_name'])) {
            $shopimageName = $data['shop_image'][0]['name'];
            unset($data['shop_image']);
            $data['shop_image']= $this->imageUploader->moveFileFromTmp($shopimageName);
        } elseif (isset($data['shop_image'][0]['name']) && !isset($data['shop_image'][0]['tmp_name'])) {
            $shopimageName = $data['shop_image'][0]['name'];
            unset($data['shop_image']);
            $data['shop_image']= $shopimageName;
        } else {
            $data['shop_image'] = '';
        }
        $finalData['lists'] = $data;
        return $finalData;
    }
}
