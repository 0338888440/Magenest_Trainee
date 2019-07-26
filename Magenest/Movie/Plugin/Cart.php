<?php
namespace Magenest\Movie\Plugin;
class Cart
{
    protected $quote;
    protected $request;
    protected $configurableproduct;
    protected $urlinterface;
    protected $productrepository;

    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\App\Request\Http $request,
        \Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurableproduct,
        \Magento\Framework\UrlInterface $urlinterface,
        \Magento\Catalog\Model\ProductRepository $productrepository
    )
    {
        $this->quote = $checkoutSession->getQuote ();
        $this->request = $request;
        $this->configurableproduct = $configurableproduct;
        $this->urlinterface = $urlinterface;
        $this->productrepository = $productrepository;
    }

    public function beforeAddProduct($subject, $productInfo, $requestInfo = null)
    {
        $paramsData = $this->request->getParams ();
        $productId = $paramsData['product'];
        $product = $this->productrepository->getById ($productId);
        if ($product->getTypeId () == \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE) {
            $attributes = $paramsData['super_attribute'];

            $new_product = $this->configurableproduct->getProductByAttributes ($attributes, $product);
            $new_productId = $new_product->getID ();

            $productInfo = $new_productId;
        }

        return array($productInfo, $requestInfo);
    }
}