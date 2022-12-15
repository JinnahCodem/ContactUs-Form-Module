<?php declare(strict_types=1);

/*
 * Copyright (c) 2022 ReCodem Pvt Ltd All rights reserved
 */

namespace Codem\MobileLogin\Setup\Patch\Data;

use Magento\Customer\Model\Customer;
use Magento\Customer\Model\ResourceModel\Attribute;
use Magento\Eav\Model\Config;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Psr\Log\LoggerInterface;
use Zend_Validate_Exception;

/**
 * Class AddMobileAttribute to add the registered mobile number to the database
 */
class AddMobileAttribute implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;
    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;
    /**
     * @var Config
     */
    private $eavConfig;
    /**
     * @var Attribute
     */
    private $attributeResource;
    /**
     * AddMobileAttribute constructor.
     * @param EavSetupFactory $eavSetupFactory
     * @param Config $eavConfig
     * @param LoggerInterface $logger
     * @param Attribute $attributeResource
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        Config $eavConfig,
        Attribute $attributeResource,
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->eavConfig = $eavConfig;
        $this->attributeResource = $attributeResource;
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * @return AddMobileAttribute|void
     * @throws AlreadyExistsException
     * @throws LocalizedException
     * @throws Zend_Validate_Exception
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $this->addMobileAttribute();
        $this->moduleDataSetup->getConnection()->endSetup();
    }
    /**
     * @throws AlreadyExistsException
     * @throws LocalizedException
     * @throws Zend_Validate_Exception
     */
    public function addMobileAttribute()
    {
        $eavSetup = $this->eavSetupFactory->create();
        $eavSetup->addAttribute(
            Customer::ENTITY,
            'mobile_number',
            [
                'type' => 'varchar',
                'label' => 'Mobile Number',
                'input' => 'text',
                'required' => 0,
                'visible' => 1,
                'user_defined' => 1,
                'sort_order' => 85,
                'position' => 85,
                'system' => 0,
                'is_used_in_grid' => 1,
                'is_visible_in_grid' => 1,
                'is_filterable_in_grid' => 1,
                'is_searchable_in_grid' => 1,
                'grid_filter_condition_type' => 1
            ]
        );
        $attributeSetId = $eavSetup->getDefaultAttributeSetId(Customer::ENTITY);
        $attributeGroupId = $eavSetup->getDefaultAttributeGroupId(Customer::ENTITY);
        $attribute = $this->eavConfig->getAttribute(Customer::ENTITY, 'mobile_number');
        $attribute->setData('attribute_set_id', $attributeSetId);
        $attribute->setData('attribute_group_id', $attributeGroupId);
        $attribute->setData('used_in_forms', ['adminhtml_checkout',
            'adminhtml_customer',
            'adminhtml_customer_address',
            'customer_account_create',
            'customer_account_edit',
            'customer_address_edit',
            'customer_register_address',
        ]);
        $this->attributeResource->save($attribute);
    }

    /**
     * @return array|string[]
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @return void
     */
    public function revert()
    {
    }

    /**
     * @return array|string[]
     */
    public function getAliases()
    {
        return [];
    }
}
