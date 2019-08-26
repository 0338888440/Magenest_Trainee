<?php

namespace Magenest\Cybergame\Controller\Account;

class CountRoom extends \Magento\Framework\App\Action\Action
{
    protected $_roomExtraOption;

    public function __construct(\Magenest\Cybergame\Model\ResourceModel\RoomExtraOption\CollectionFactory $collectionFactory,
                                \Magento\Framework\App\Action\Context $context)
    {
        parent::__construct ($context);
        $this->_roomExtraOption = $collectionFactory;
    }

    public function execute()
    {
        $countRoom = json_encode($this->_roomExtraOption->create ()->getSize ());
        echo $countRoom;
        return;
    }
}