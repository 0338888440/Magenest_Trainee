<?php
namespace Magenest\Movie\Controller\Adminhtml\Director;

class Save extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE = 'Director';

    protected $resultPageFactory;
    protected $_directorFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magenest\Movie\Model\DirectorFactory $directorFactory
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->_directorFactory = $directorFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if($data)
        {
            $id = $data['director_id'];

            $contact = $this->_directorFactory->create()->load($id);

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
