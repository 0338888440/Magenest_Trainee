<?php

namespace Magenest\Movie\Controller\Adminhtml\Director;

use Magenest\Movie\Model\Movie as Contact;
use function GuzzleHttp\Psr7\modify_request;


class Delete extends \Magento\Backend\App\Action
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
        parent::__construct ($context);
    }

    public function execute()
    {
        $id = $this->getRequest ()->getParam ('id');

        $movie = $this->_directorFactory->create ()->load ($id);

        if (!$movie) {
            $this->messageManager->addError (__ ('Unable to process. please, try again.'));
            $resultRedirect = $this->resultRedirectFactory->create ();
            return $resultRedirect->setPath ('*/*/', array('_current' => true));
        }

        try {
            $movie->delete ();
            $this->messageManager->addSuccess (__ ('Your Director has been deleted !'));
        } catch (\Exception $e) {
            $this->messageManager->addError (__ ('Error while trying to delete contact'));
            $resultRedirect = $this->resultRedirectFactory->create ();
            return $resultRedirect->setPath ('*/*/index', array('_current' => true));
        }

        $resultRedirect = $this->resultRedirectFactory->create ();
        return $resultRedirect->setPath ('*/*/index', array('_current' => true));
    }
}