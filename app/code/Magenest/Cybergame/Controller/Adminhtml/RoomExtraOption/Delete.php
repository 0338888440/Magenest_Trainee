<?php

namespace Magenest\Cybergame\Controller\Adminhtml\RoomExtraOption;

class Delete extends \Magento\Backend\App\Action
{
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
            $col = $objManager->create ('Magenest\Cybergame\Model\RoomExtraOption')->load ($id);
            $col->delete();
        } elseif (isset($_POST['excluded'])){
            $sql = "delete from room_extra_option";
            $connection->query($sql);
        }else{
            foreach ($_POST['selected'] as $item){
                $col = $objManager->create ('Magenest\Cybergame\Model\RoomExtraOption')->load ($item);
                $col->delete ();
            }
        }
        $this->messageManager->addSuccess (__ ('Your Room has been deleted !'));

        $resultRedirect = $this->resultRedirectFactory->create ();
        return $resultRedirect->setPath ('*/*/index', array('_current' => true));
    }
}