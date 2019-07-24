<?php
namespace Magenest\Movie\Controller\Adminhtml\Actor;

class Save extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE = 'Actor';

    protected $resultPageFactory;
    protected $_actorFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magenest\Movie\Model\ActorFactory $actorFactory
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->_actorFactory = $actorFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if($data)
        {
            $id = $data['actor_id'];

            $contact = $this->_actorFactory->create()->load($id);

            $data = array_filter($data, function($value) {return $value !== ''; });

            $contact->setData($data);
            $contact->save();
            $this->messageManager->addSuccess(__('Successfully saved the item.'));
            $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
            return $resultRedirect->setPath('*/*/');
        }

        return $resultRedirect->setPath('*/*/');
    }
}
