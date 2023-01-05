<?php declare(strict_types=1);

/*
 * Copyright (c) 2023 ReCodem Pvt Ltd All rights reserved
 */

namespace Codem\Shoplist\Model\Resolver;

use Codem\Shoplist\Model\ResourceModel\Lists\CollectionFactory as ShopCollectionFactory;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\Resolver\Value;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

/**
 * Class to list shop using Identifier in Graphqi Api
 */
class ShopsByIdentifier implements ResolverInterface
{

    /**
     * @var ShopCollectionFactory $shopCollection
     */
    protected $shopCollection;

    /**
     * @param ShopCollectionFactory $shopCollection
     */
    public function __construct(
        ShopCollectionFactory $shopCollection
    ) {
        $this->shopCollection = $shopCollection;
    }

    /**
     * @param Field $field
     * @param $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array|Value|mixed|null
     * @throws GraphQlNoSuchEntityException
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        try {
            $collection = $this->shopCollection->create()
                ->addFieldToFilter('identifier', ['eq' => [$args['identifier']]])
                ->getFirstItem();
            $shopById =  $collection->getData();
            if (sizeof($shopById) == 0) {
                throw new GraphQlNoSuchEntityException(__('The Shop doesn\'t exists for the given shop id'));
            }
        } catch (Exception $e) {
            return  $e->getMessage();
        }

        return $shopById;
    }
}
