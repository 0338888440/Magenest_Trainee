<?php

namespace Magenest\Movie\Controller\Adminhtml\Movie;

use Magenest\Movie\Model\Movie as Contact;


class Delete extends \Magento\Backend\App\Action
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
        parent::__construct ($context);
    }

    public function execute()
    {
        $id = $this->getRequest ()->getParam ('id');

        $movie = $this->_movieFactory->create ()->load ($id);

        if (!$movie) {
            $this->messageManager->addError (__ ('Unable to process. please, try again.'));
            $resultRedirect = $this->resultRedirectFactory->create ();
            return $resultRedirect->setPath ('*/*/', array('_current' => true));
        }

        try {
            $movie->delete ();
            $this->messageManager->addSuccess (__ ('Your Movie has been deleted !'));
        } catch (\Exception $e) {
            $this->messageManager->addError (__ ('Error while trying to delete Movie'));
            $resultRedirect = $this->resultRedirectFactory->create ();
            return $resultRedirect->setPath ('*/*/index', array('_current' => true));
        }

        $resultRedirect = $this->resultRedirectFactory->create ();
        return $resultRedirect->setPath ('*/*/index', array('_current' => true));
    }
}