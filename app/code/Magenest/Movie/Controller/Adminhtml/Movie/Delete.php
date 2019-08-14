<?php

namespace Magenest\Movie\Controller\Adminhtml\Movie;

use Magenest\Movie\Model\Movie as Contact;
use phpDocumentor\Reflection\Types\This;


class Delete extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE = 'Movie';

    protected $resultPageFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct ($context);
    }

    public function execute()
    {
        $objManager = \Magento\Framework\App\ObjectManager::getInstance ();
        $resource = $objManager->get ('Magento\Framework\App\ResourceConnection');
        $connection =$resource->getConnection();
        $id = $this->getRequest ()->getParam ('id');

        if ($id != null)
        {
            $movie = $objManager->create ('Magenest\Movie\Model\Movie')->load ($id);
            $movie->delete();
        } elseif (isset($_POST['excluded'])){
            $sql = "delete from magenest_movie";
            $connection->query($sql);
        }else{
            foreach ($_POST['selected'] as $item){
                $movie = $objManager->create ('Magenest\Movie\Model\Movie')->load ($item);
                $movie->delete ();
            }
        }
        $this->messageManager->addSuccess (__ ('Your Movie has been deleted !'));

        $resultRedirect = $this->resultRedirectFactory->create ();
        return $resultRedirect->setPath ('*/*/index', array('_current' => true));
    }
}