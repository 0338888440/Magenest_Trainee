<?php

namespace Magenest\Cybergame\Block\Product;

use Magento\Catalog\Block\Product\View;
use Magento\Catalog\Api\ProductRepositoryInterface;


/**
 * Class UpdateRoomInfo
 * @package Magenest\Cybergame\Block
 */
class ExtraInfo extends View
{
    protected $_productFactory;
    protected $_roomExtraOption;

    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Catalog\Helper\Product $productHelper,
        \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        \Magento\Customer\Model\Session $customerSession,
        ProductRepositoryInterface $productRepository,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        array $data = [],
        \Magento\Catalog\Model\ProductFactory $productCollectionFactory,
        \Magenest\Cybergame\Model\ResourceModel\RoomExtraOption\CollectionFactory $collectionFactory)
    {
        $this->_productFactory = $productCollectionFactory;
        $this->_roomExtraOption = $collectionFactory;
        parent::__construct ($context, $urlEncoder, $jsonEncoder, $string, $productHelper, $productTypeConfig, $localeFormat, $customerSession, $productRepository, $priceCurrency, $data);
    }

    public function getRoomOptionCollection()
    {
        $id = $this->getRequest ()->getParam ('id');
        $collection = $this->_roomExtraOption->create ();

        $firstItem = $collection->addFieldToSelect ('*')->addFieldToFilter ('product_id', $id)->getFirstItem ();

        return $firstItem;
    }

    public function getNameProductById()
    {
        $id = $this->getRequest ()->getParam ('id');
        $coll = $this->_productFactory->create ()->load ($id);
        return $coll->getData ('name');
    }

    public function getPriceProductById()
    {
        $id = $this->getRequest ()->getParam ('id');
        $coll = $this->_productFactory->create ()->load ($id);
        return $coll->getData ('price');
    }
    public function getIdProduct(){
        return $this->getRequest ()->getParam ('id');
    }
}