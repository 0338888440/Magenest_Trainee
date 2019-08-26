<?php

namespace Magenest\Cybergame\Controller\UpdateRoomInfo;

class Save extends \Magento\Framework\App\Action\Action
{
    public function __construct(
        \Magento\Framework\App\Action\Context $context)
    {
        parent::__construct ($context);
    }

    public function execute()
    {
        $data=$this->getRequest ()->getPostValue ();
        $id = $this->getRequest ()->getParam ('id');
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance ();
        $roomExtraOption = $objectManager->create('\Magenest\Cybergame\Model\RoomExtraOption');

        $roomExtraOptionColl = $roomExtraOption->load($id);
        $roomExtraOptionColl->setData($data);
        $roomExtraOptionColl->save();
        $this->_redirect('*/*/');
        $this->messageManager->addSuccess(__('Your values has beeen saved successfully.'));
    }
}