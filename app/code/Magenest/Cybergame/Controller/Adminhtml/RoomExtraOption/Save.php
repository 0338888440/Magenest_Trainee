<?php

namespace Magenest\Cybergame\Controller\Adminhtml\RoomExtraOption;

class Save extends \Magento\Backend\App\Action
{
    protected $resultPageFactory;
    protected $_roomExtraOptionFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magenest\Cybergame\Model\RoomExtraOptionFactory $roomExtraOptionFactory
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->_roomExtraOptionFactory = $roomExtraOptionFactory;
        parent::__construct ($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create ();
        $data = $this->getRequest ()->getPostValue ();

        if ($data) {
            $idData = $data['id'];
            $roomExtraOption = $this->_roomExtraOptionFactory->create ()->load ($idData);

            $data = array_filter ($data, function ($value) {
                return $value !== '';
            });

            $roomExtraOption->setData ($data);
            $roomExtraOption->save ();


            $this->messageManager->addSuccess (__ ('Successfully saved the item.'));
            $this->_objectManager->get ('Magento\Backend\Model\Session')->setFormData (false);

            return $resultRedirect->setPath ('*/*/');
        }

        return $resultRedirect->setPath ('*/*/');
    }
    private function getNewId()
    {
        $colNewId = $this->_roomExtraOptionFactory->create ()
            ->getCollection ()->addFieldToSelect ('id')
            ->setOrder ('id', 'DESC')
            ->setPageSize (1);
        $newId = $colNewId->getFirstItem ()->getData ('id');
        return $newId;
    }
}
