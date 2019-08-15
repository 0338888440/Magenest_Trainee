<?php

namespace Magenest\FrontEnd\Controller\Index;

class GetStreet extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;
    protected $_resultRawFactory;
    protected $_httpClientFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\HTTP\ZendClientFactory $httpClientFactory
    )
    {
        $this->_httpClientFactory = $httpClientFactory;
        $this->_resultRawFactory = $resultRawFactory;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct ($context);
    }

    public function execute()
    {
        $id = $this->getRequest ()->getParam ('street');
        $url = "https://thongtindoanhnghiep.co/api/district/" . $id . "/ward";
//        $this->_curl->get($url);
//        $content = $this->_curl->getBody();
        $content = $this->_httpClientFactory->create ();
        $content->setUri ($url);
        $content->setConfig (['maxredirects' => 0, 'timeout' => 20]);
        $result = $content->request ()->getBody ();
//        $content = file_get_contents ($url);
        /** @var \Magento\Framework\Controller\Result\Raw $response */
        $response = $this->_resultRawFactory->create ();
        $response->setHeader ('Content-type', 'text/plain');
        $response->setContents ($result);
        return $response;
    }
}