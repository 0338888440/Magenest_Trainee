<?php

namespace Magenest\FrontEnd\Controller\Index;
class GetStreet extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;
    protected $_resultRawFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
    )
    {
        $this->_resultRawFactory = $resultRawFactory;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct ($context);
    }

    public function execute()
    {
        $id = $this->getRequest ()->getParam ('street');
        $url = "https://thongtindoanhnghiep.co/api/district/".$id."/ward";
        $content = file_get_contents ($url);
//        echo json_encode($content);
        /** @var \Magento\Framework\Controller\Result\Raw $response */
        $response = $this->_resultRawFactory->create ();
        $response->setHeader ('Content-type', 'text/plain');
        $response->setContents ($content);
        return $response;
    }
}