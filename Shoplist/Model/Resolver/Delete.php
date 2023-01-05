<?php declare(strict_types=1);

/*
 * Copyright (c) 2023 ReCodem Pvt Ltd All rights reserved
 */

namespace Codem\Shoplist\Model\Resolver;

use Codem\Shoplist\Api\ShopRepositoryInterface;
use Exception;
use Magento\Framework\Exception\StateException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

/**
 * Delete shoplist using shop id in Graphqi Api
 */
class Delete implements ResolverInterface
{

    /**
     * @var ShopRepositoryInterface
     */
    protected $shopRepository;

    /**
     * Update constructor.
     * @param ShopRepositoryInterface $shopRepository
     */
    public function __construct(
        ShopRepositoryInterface $shopRepository
    ) {
        $this->shopRepository = $shopRepository;
    }

    /**
     * @param Field $field
     * @param $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        try {
            $shopId = $args['input']['shop_id'];
            if (is_string($this->shopRepository->getById($shopId))) {
                throw new GraphQlNoSuchEntityException(__('The shop doesn\'t exists.'));
            }
            $this->shopRepository->delete($shopId);
            $message['message'] = __('Shop has been successfully deleted');

        } catch (Exception $e) {
            $message['message'] =  $e->getMessage();
        }
        return $message;

    }
}
