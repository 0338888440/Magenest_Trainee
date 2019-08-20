<?php

namespace Magenest\ImportReview\Controller\Adminhtml\Import;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\ResultFactory;

class Upload extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    protected $uploaderFactory;
    protected $varDirectory;
    protected $csvProcessor;
    protected $storeID;
    protected $product;
    protected $reviewFactory;
    protected $customerFactory;
    protected $reviewProductEntityId;
    protected $reviewCollectionFactory;
    protected $ratingFactory;
    protected $objectManager;
    /**
     * @var array
     */
    protected $ratings;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\File\Csv $csvProcessor,
        \Magento\Store\Model\StoreManager $storeManager,
        \Magento\Review\Model\ReviewFactory $reviewFactory,
        \Magento\Review\Model\ResourceModel\Review\CollectionFactory $reviewCollectionFactory,
        \Magento\Review\Model\RatingFactory $ratingFactory,
        \Magento\Customer\Model\CustomerFactory $customerFactory
    )
    {
        parent::__construct ($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->uploaderFactory = $uploaderFactory;
        $this->varDirectory = $filesystem->getDirectoryWrite (DirectoryList::VAR_DIR); // Get default 'var' directory
        $this->csvProcessor = $csvProcessor;
        $this->storeID = $storeManager->getStore ()->getId ();
        $this->reviewFactory = $reviewFactory;
        $this->customerFactory = $customerFactory;
        $this->ratingFactory = $ratingFactory;
        $this->reviewCollectionFactory = $reviewCollectionFactory;
        $this->objectManager = \Magento\Framework\App\ObjectManager::getInstance ();
    }

    public function execute()
    {
        $resultRedirect = $this->resultFactory->create (ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath ('import_review/import/index');
        try {
            /**
             * fileId field must be the same name as the upload_field name from the form block
             * see Magenest\ImportReview\Block\Adminhtml\Edit\Form
             */
            $uploader = $this->uploaderFactory->create (['fileId' => 'reviews_import_file']);
            $uploader->checkAllowedExtension ('csv');
            $uploader->skipDbProcessing (true);
            $result = $uploader->save ($this->getWorkingDir ());
            $this->validateIfHasExtension ($result);
        } catch (\Exception $e) {
            $this->messageManager->addError (__ ($e->getMessage ()));
            return $resultRedirect;
        }
        $this->processUpload ($result);
        $this->messageManager->addSuccess (__ ('Reviews imported'));

        return $resultRedirect;
        //return  $resultPage = $this->resultPageFactory->create();
    }

    public function validateIfHasExtension($result)
    {
        $extension = pathinfo ($result['file'], PATHINFO_EXTENSION);

        $uploadedFile = $result['path'] . $result['file'];
        if (!$extension) {
            $this->varDirectory->delete ($uploadedFile);
            throw new \Exception(__ ('The file you uploaded has no extension.'));
        }
    }

    public function getWorkingDir()
    {
        return $this->varDirectory->getAbsolutePath ('importexport/');
    }

    /**
     * Process uploaded csv file
     *
     * @param [type] $result
     * @return void
     */
    public function processUpload($result)
    {
        $sourceFile = $this->getWorkingDir () . $result['file'];

        $rows = $this->csvProcessor->getData ($sourceFile);
        $header = array_shift ($rows);

        // See \Magento\ReviewSampleData\Model\Review::install()
        foreach ($rows as $row) {
            $data = [];
            foreach ($row as $key => $value) {
                $data[$header[$key]] = $value;
            }
            $row = $data;
            $productId = $row['product_id'];
            if (empty($productId)) {
                //If product id is used as sku
                $productId = $this->objectManager->get ('Magento\Catalog\Model\Product')->getIdBySku ($row['sku']);
                $row['product_id'] = $productId;
                if (empty($productId)) {
                    continue;
                }
            }
            $review = $this->prepareReview ($row);
            /** @var \Magento\Review\Model\ResourceModel\Review\Collection $reviewCollection */
            $reviewCollection = $this->reviewCollectionFactory->create ();
            $reviewCollection->addFilter ('entity_pk_value', $productId)
                ->addFilter ('entity_id', $this->getReviewEntityId ())
                ->addFieldToFilter ('detail.title', ['eq' => $row['title']]);
            if ($reviewCollection->getSize () > 0) {
                continue;
            }
            if ($this->emailValid ($row['email'])) {
                if (!empty($row['email']) && ($this->getUserId ($row['email']) != null)) {
                    $review->setCustomerId ($this->getUserId ($row['email']));
                }
            } else {
                continue;
            }
            if (empty($row['email']) || empty($row['nickname']) || empty($row['title']) || empty($row['detail']) || empty($row['create_at'])) {
                continue;
            }
            $review->save ();
        }
    }

    /**
     * See \Magento\ReviewSampleData\Model\Review::prepareReview()
     * @param array $row
     * @return \Magento\Review\Model\Review
     */
    protected function prepareReview($row)
    {
        /** @var $review \Magento\Review\Model\Review */
        $review = $this->reviewFactory->create ();
        $review->setEntityId (
            $review->getEntityIdByCode (\Magento\Review\Model\Review::ENTITY_PRODUCT_CODE)
        )->setEntityPkValue (
            $row['product_id']
        )->setNickname (
            $row['nickname']
        )->setTitle (
            $row['title']
        )->setDetail (
            $row['detail']
        )->setStatusId (
            $row['status']
        )->setStoreId (
            $this->storeID
        )->setStores (
            [$this->storeID]
        )->setCreatedAt (
            $this->convertDate ($row['create_at'])
        );
        return $review;
    }

    public function getUserId($email)
    {
        $customer = $this->customerFactory->create ();
        $customer->setWebsiteId ($this->storeID);
        $customer->loadByEmail ($email);
        return $customer->getEntityId ();
    }

    /**
     * Converts date to mysql formatted date
     *
     * @param string $date
     * @return string convertedDate
     */
    public function convertDate($date)
    {
        $timestamp = strtotime ($date);
        return date ("Y-m-d H:i:s", $timestamp);
    }

    /**
     * @return int review ID
     */
    protected function getReviewEntityId()
    {
        if (!$this->reviewProductEntityId) {
            /** @var $review \Magento\Review\Model\Review */
            $review = $this->reviewFactory->create ();
            $this->reviewProductEntityId = $review->getEntityIdByCode (
                \Magento\Review\Model\Review::ENTITY_PRODUCT_CODE
            );
        }
        return $this->reviewProductEntityId;
    }

    public function emailValid($string)
    {
        if (preg_match ("/^([a-zA-Z0-9])+([a-zA-Z0-9._-])*@([a-zA-Z0-9])+\.[A-Za-z]{2,6}$/", $string))
            return true;
        return false;
    }
}