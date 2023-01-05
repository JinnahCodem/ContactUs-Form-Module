<?php declare(strict_types=1);

namespace Codem\Shoplist\Model\Resolver;

use Codem\Shoplist\Api\Data\ShopInterface;
use Codem\Shoplist\Api\ShopRepositoryInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

/**
 * Class to update the shop using shop id in Graphqi Api
 */
class Update implements ResolverInterface
{
    /**
     * @var ShopRepositoryInterface
     */
    protected $shopRepository;

    /**
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
     * @throws CouldNotSaveException
     * @throws GraphQlAuthorizationException
     * @throws GraphQlNoSuchEntityException
     * @throws NoSuchEntityException
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        try {
            $shopId = $args['input'][ShopInterface::SHOP_ID];
            $shopLists = $this->shopRepository->getById($shopId);

            if (is_string($shopLists)) {
                throw new GraphQlAuthorizationException(__('The shop doesn\'t exists.'));
            }
            if ($shopLists->getShopId()) {
                if (isset($args['input'][ShopInterface::NAME])) {
                    $shopLists->setShopName($args['input'][ShopInterface::NAME]);
                }
                if (isset($args['input'][ShopInterface::IDENTIFIER])) {
                    $shopLists->setIdentifier($args['input'][ShopInterface::IDENTIFIER]);
                }
                if (isset($args['input'][ShopInterface::SHOP_IMAGE])) {
                    $shopLists->setShopImage($args['input'][ShopInterface::SHOP_IMAGE]);
                }
                if (isset($args['input'][ShopInterface::COUNTRY])) {
                    $shopLists->setCountry($args['input'][ShopInterface::COUNTRY]);
                }
                if (isset($args['input'][ShopInterface::STORE_VIEW])) {
                    $shopLists->setStoreView($args['input'][ShopInterface::STORE_VIEW]);
                }

                $this->shopRepository->save($shopLists);
            } else {
                throw new GraphQlNoSuchEntityException(__('The Shop doesn\'t exists for the given shop id'));
            }
        } catch (Exception $e) {
            $thanks_message['success_message']=  $e->getMessage();
        }
        $thanks_message['success_message'] = __("Shop has been successfully updated");
        return $thanks_message;
    }
}
