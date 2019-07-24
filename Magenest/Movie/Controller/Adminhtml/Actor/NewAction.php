<?php
namespace Magenest\Movie\Controller\Adminhtml\Actor;

class NewAction extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE = 'Actor';

    protected $resultPageFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory)
    {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        return $this->resultPageFactory->create();
    }
}