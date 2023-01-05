<?php declare(strict_types=1);

namespace Codem\Shoplist\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class Image for retrieving the image from base path and display in shop lists grid
 * @package Codem\Shoplist\Ui\Component\Listing\Column
 */
class Image extends Column
{

    /**
     * constant for the image alt field
     */
    const ALT_FIELD = 'shop_name';

    /**
     * stored image path
     */
    const IMAGE_PATH = 'shopListImage/image/';

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var UrlInterface
     */
    protected $_urlBuilder;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param StoreManagerInterface $storeManager
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        StoreManagerInterface $storeManager,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->_storeManager = $storeManager;
        $this->_urlBuilder = $urlBuilder;
    }

    /**
     * @param array $dataSource
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['shop_image']) && !empty($item['shop_image'])) {
                    $mediaRelativePath = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
                    $imagePath = $mediaRelativePath . self::IMAGE_PATH . $item['shop_image'];
                    $item[$fieldName . '_src'] = $imagePath;
                    $item[$fieldName . '_alt'] = $this->getAlt($item);
                    $item[$fieldName . '_link'] = $this->_urlBuilder->getUrl(
                        'shoplist/index/edit',
                        ['id' => $item['shop_id']]
                    );
                    $item[$fieldName . '_orig_src'] = $imagePath;
                }
            }
        }

        return $dataSource;
    }

    /**
     * @param array $row
     * @return mixed|null
     */
    protected function getAlt(array $row)
    {
        $altField = self::ALT_FIELD;
        return isset($row[$altField]) ? $row[$altField] : null;
    }
}
