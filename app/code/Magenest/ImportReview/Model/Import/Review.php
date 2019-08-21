<?php

namespace Magenest\ImportReview\Model\Import;


use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface;

/**
 * Class Review
 * @package Magenest\ImportReview\Model\Import
 */
class Review extends \Magento\ImportExport\Model\Import\Entity\AbstractEntity
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $uploaderFactory;
    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    protected $varDirectory;
    /**
     * @var \Magento\Framework\File\Csv
     */
    protected $csvProcessor;
    /**
     * @var int
     */
    protected $storeID;
    /**
     * @var
     */
    protected $product;
    /**
     * @var \Magento\Review\Model\ReviewFactory
     */
    protected $reviewFactory;
    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $customerFactory;
    /**
     * @var
     */
    protected $reviewProductEntityId;
    /**
     * @var \Magento\Review\Model\ResourceModel\Review\CollectionFactory
     */
    protected $reviewCollectionFactory;
    /**
     * @var \Magento\Review\Model\RatingFactory
     */
    protected $ratingFactory;
    /**
     * @var \Magento\Framework\App\ObjectManager
     */
    protected $objectManager;
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    protected $validColumnNames = ['product_id','sku','email','nickname','title','detail','create_at','status'];

    public function __construct(
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\ImportExport\Helper\Data $importExportData,
        \Magento\ImportExport\Model\ResourceModel\Import\Data $importData,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\ImportExport\Model\ResourceModel\Helper $resourceHelper,
        ProcessingErrorAggregatorInterface $errorAggregator,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\File\Csv $csvProcessor,
        \Magento\Store\Model\StoreManager $storeManager,
        \Magento\Review\Model\ReviewFactory $reviewFactory,
        \Magento\Review\Model\ResourceModel\Review\CollectionFactory $reviewCollectionFactory,
        \Magento\Review\Model\RatingFactory $ratingFactory,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Framework\App\Request\Http $request

    )
    {
        $this->dateTime = $dateTime;
        $this->jsonHelper = $jsonHelper;
        $this->_importExportData = $importExportData;
        $this->_resourceHelper = $resourceHelper;
        $this->_dataSourceModel = $importData;
        $this->request = $request;
        $this->_connection = $resource->getConnection ('write');
        $this->errorAggregator = $errorAggregator;
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

//
    public function getValidColumnNames()
    {
        return $this->validColumnNames;
    }

    /**
     * @param array $rowData
     * @param int $rowNum
     * @return bool
     */
    public function validateRow(array $rowData, $rowNum)
    {
        return true;
    }

    /**
     * @return string
     */
    public function getEntityTypeCode()
    {
        return 'import_review';
    }

    /**
     * @return bool
     */
    protected function _importData()
    {
        if (\Magento\ImportExport\Model\Import::BEHAVIOR_DELETE == $this->getBehavior ()) {
        } elseif (\Magento\ImportExport\Model\Import::BEHAVIOR_REPLACE == $this->getBehavior ()) {
        } elseif (\Magento\ImportExport\Model\Import::BEHAVIOR_APPEND == $this->getBehavior ()) {
            $this->saveReviews ();
        }
        return true;
    }

    /**
     * @return $this
     */
    public function saveReviews()
    {
        $this->saveAndReplaceReviews ();
        return $this;
    }

    /**
     * @throws \Exception
     */
    public function saveAndReplaceReviews()
    {
        $post = $this->request->getFiles ();
        $rows = $this->csvProcessor->getData ($post['import_file']['tmp_name']);
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
     * @param $row
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

    /**
     * @param $email
     * @return mixed
     */
    public function getUserId($email)
    {
        $customer = $this->customerFactory->create ();
        $customer->setWebsiteId ($this->storeID);
        $customer->loadByEmail ($email);
        return $customer->getEntityId ();
    }

    /**
     * @param $date
     * @return false|string
     */
    public function convertDate($date)
    {
        $timestamp = strtotime ($date);
        return date ("Y-m-d H:i:s", $timestamp);
    }

    /**
     * @return bool|int
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

    /**
     * @param $string
     * @return bool
     */
    public function emailValid($string)
    {
        if (preg_match ("/^([a-zA-Z0-9])+([a-zA-Z0-9._-])*@([a-zA-Z0-9])+\.[A-Za-z]{2,6}$/", $string))
            return true;
        return false;
    }

}