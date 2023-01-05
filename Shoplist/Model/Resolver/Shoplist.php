<?php declare(strict_types=1);

/*
 * Copyright (c) 2023 ReCodem Pvt Ltd All rights reserved
 */

namespace Codem\Shoplist\Model\Resolver;

use Codem\Shoplist\Model\ResourceModel\Lists\CollectionFactory as ShopCollectionFactory;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\Value;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

/**
 * Class to list all shops in Graphqi Api
 */
class Shoplist implements ResolverInterface
{
    /**
     * @var ShopCollectionFactory
     */
    protected $shopCollection;

    public function __construct(ShopCollectionFactory $shopCollection)
    {
        $this->shopCollection = $shopCollection;
    }

    /**
     * @param Field $field
     * @param $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array|Value|mixed|null
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        return $this->shopCollection->create()->getData();
    }
}
