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
            $movie = $this->_movieFactory->create()->load($id);

            $data = array_filter($data, function($value) {return $value !== ''; });

            $movie->setData($data);
            $movie->save();
            $this->messageManager->addSuccess(__('Successfully saved the item.'));
            $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);

            $colNewId = $this->_movieFactory->create ()
                    ->getCollection()->addFieldToSelect ('movie_id')
                    ->setOrder('movie_id','DESC')
                    ->setPageSize(1);
            $newId = $colNewId->getFirstItem()->getData('movie_id');
            $this->_eventManager->dispatch('save_movie',['movie_id' => $newId]);

            return $resultRedirect->setPath('*/*/');
        }

        return $resultRedirect->setPath('*/*/');
    }
}
