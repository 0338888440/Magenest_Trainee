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

    /**
     * @var array
     */
    protected $validColumnNames = ['product_id', 'sku', 'email', 'nickname', 'title', 'detail', 'create_at', 'status'];

    /**
     * Review constructor.
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \Magento\ImportExport\Helper\Data $importExportData
     * @param \Magento\ImportExport\Model\ResourceModel\Import\Data $importData
     * @param \Magento\Framework\App\ResourceConnection $resource
     * @param \Magento\ImportExport\Model\ResourceModel\Helper $resourceHelper
     * @param ProcessingErrorAggregatorInterface $errorAggregator
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Framework\File\Csv $csvProcessor
     * @param \Magento\Store\Model\StoreManager $storeManager
     * @param \Magento\Review\Model\ReviewFactory $reviewFactory
     * @param \Magento\Review\Model\ResourceModel\Review\CollectionFactory $reviewCollectionFactory
     * @param \Magento\Review\Model\RatingFactory $ratingFactory
     * @param \Magento\Customer\Model\CustomerFactory $customerFactory
     * @param \Magento\Framework\App\Request\Http $request
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
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

    /**
     * @return array
     */
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
        $productId = $rowData['product_id'];
        if (empty($productId)) {
            $productId = $this->objectManager->get ('Magento\Catalog\Model\Product')->getIdBySku ($rowData['sku']);
            $rowData['product_id'] = $productId;
            if (empty($productId)) {
                $this->addRowError ("Didn't have product by product_id or sku!", $rowNum);
            }
        } else {
            $prodColl = $this->objectManager->create ('Magento\Catalog\Model\Product')->load ($productId);
            if (empty($prodColl)) {
                $productId = $this->objectManager->get ('Magento\Catalog\Model\Product')->getIdBySku ($rowData['sku']);
                $rowData['product_id'] = $productId;
                if (empty($productId)) {
                    $this->addRowError ("Didn't have product by product_id or sku! ", $rowNum);
                }
            }
        }
        if ($rowData['status'] != 1 && $rowData['status'] != 2 && $rowData['status'] != 3) {
            $this->addRowError ("Status must be equal 1-(Approved), 2-(Pending) or 3-(Not Approved)!", $rowNum);
        }
        if (empty($rowData['email'])) {
            $this->addRowError ("Please import email! ", $rowNum);
        } else {
            if (!$this->emailValid ($rowData['email'])) {
                $this->addRowError ("Please import right form of email! ", $rowNum);
            }
        }
        if (empty($rowData['nickname'])) {
            $this->addRowError ("Please import nickname! ", $rowNum);
        }
        if (empty($rowData['title'])) {
            $this->addRowError ("Please import title! ", $rowNum);
        }
        if (empty($rowData['detail'])) {
            $this->addRowError ("Please import detail! ", $rowNum);
        }
        if (empty($rowData['create_at'])) {
            $this->addRowError ("Please import time create_at! ", $rowNum);
        }
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
                $productId = $this->objectManager->get ('Magento\Catalog\Model\Product')->getIdBySku ($row['sku']);
                $row['product_id'] = $productId;
                if (empty($productId)) {
                    continue;
                }
            } else {
                $prodColl = $this->objectManager->get ('Magento\Catalog\Model\Product')->load ($productId);
                if (!empty($prodColl)) {
                    continue;
                }
            }
            /** @var \Magento\Review\Model\ResourceModel\Review\Collection $reviewCollection */
            $reviewCollection = $this->reviewCollectionFactory->create ();
            $reviewCollection->addFilter ('entity_pk_value', $productId)
                ->addFilter ('entity_id', $this->getReviewEntityId ())
                ->addFieldToFilter ('detail.title', ['eq' => $row['title']]);
            if ($reviewCollection->getSize () > 0) {
                continue;
            }
            if ($row['status'] != 1 && $row['status'] != 2 && $row['status'] != 3) {
                continue;
            }
            if (empty($row['email']) || empty($row['nickname']) || empty($row['title']) || empty($row['detail']) || empty($row['create_at'])) {
                continue;
            }
            $review = $this->prepareReview ($row);
            if ($this->emailValid ($row['email'])) {
                if (!empty($row['email']) && ($this->getUserId ($row['email']) != null)) {
                    $review->setCustomerId ($this->getUserId ($row['email']));
                }
            } else {
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