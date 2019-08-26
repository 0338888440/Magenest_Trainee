<?php

namespace Magenest\Cybergame\Controller\UpdateRoomInfo;
class Index extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;
    protected $_customerSession;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory)
    {
        $this->_customerSession = $customerSession;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct ($context);
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create ();
        if (empty($this->getSessionID ())) {
            $this->_redirect ("customer/account/login");
        }

        return $resultPage;
    }

    public function getSessionID()
    {
        if ($this->_customerSession->isLoggedIn ())
            return $this->_customerSession->getCustomer ()->getId ();
    }
}