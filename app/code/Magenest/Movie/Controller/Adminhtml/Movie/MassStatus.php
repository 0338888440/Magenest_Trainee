<?php

namespace Magenest\Movie\Controller\Adminhtml\Movie;

use Magenest\Movie\Model\ResourceModel\Movie\CollectionFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;

// Define your collection here

class MassStatus extends \Magento\Backend\App\Action
{
    protected $filter;
    protected $collectionFactory;

    public function __construct(Context $context, Filter $filter, CollectionFactory $collectionFactory)
    {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct ($context);
    }

    public function execute()
    {
        $selected = $this->getRequest()->getPostValue('selected');
        if (is_numeric($selected)) {
            $selected[] = $selected;
        }
        $movieModel = $this->_objectManager->create('Magenest\Movie\Model\Movie');
        $count = count($selected);
        if ($count > 0) {
            for ($i = 0; $i < $count; $i++) {
                $movieModel->load($selected[$i]);
                $movieModel->setData('status', $this->getRequest()->getParam('status'));
                $movieModel->save();
            }
            $this->messageManager->addSuccess(__('A total of %1 record(s) were updated.', $count));
        } else {
            $this->messageManager->addErrorMessage(__('Update error'));
        }
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('movie/movie/index');
    }
}