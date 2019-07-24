<?php
namespace Magenest\Movie\Controller\Adminhtml\Movie;

class Save extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE = 'Movie';

    protected $resultPageFactory;
    protected $_movieFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magenest\Movie\Model\MovieFactory $movieFactory
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->_movieFactory = $movieFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if($data)
        {
            $id = $data['movie_id'];

            $contact = $this->_movieFactory->create()->load($id);

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
