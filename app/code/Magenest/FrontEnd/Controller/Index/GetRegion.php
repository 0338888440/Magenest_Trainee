<?php

namespace Magenest\FrontEnd\Controller\Index;
class GetRegion extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;
    protected $_resultRawFactory;
    protected $_curl;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\HTTP\Client\Curl $curl
    )
    {
        $this->_curl = $curl;
        $this->_resultRawFactory = $resultRawFactory;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct ($context);
    }

    public function execute()
    {
        $id = $this->getRequest ()->getParam ('region');
        $url = "https://thongtindoanhnghiep.co/api/city/" . $id . "/district";
        $this->_curl->get ($url);
        $content = $this->_curl->getBody ();
//        $content = file_get_contents ($url);
        /** @var \Magento\Framework\Controller\Result\Raw $response */
        $response = $this->_resultRawFactory->create ();
        $response->setHeader ('Content-type', 'text/plain');
        $response->setContents ($content);
        return $response;
    }
}