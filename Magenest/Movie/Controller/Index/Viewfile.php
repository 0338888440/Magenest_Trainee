<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magenest\Movie\Controller\Index;

use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Viewfile extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var \Magento\Framework\Url\DecoderInterface
     */
    protected $urlDecoder;

    public function __construct(
        Context $context,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\Url\DecoderInterface $urlDecoder)
    {
        parent::__construct ($context);

        $this->resultRawFactory = $resultRawFactory;
        $this->urlDecoder = $urlDecoder;
    }

    /**
     * Customer view file action
     *
     * @return \Magento\Framework\Controller\ResultInterface|void
     * @throws NotFoundException
     *
     * @SuppressWarnings(PHPMD.ExitExpression)
     */
    public function execute()
    {
        list($file, $plain) = $this->getFileParams ();

        /** @var \Magento\Framework\Filesystem $filesystem */
        $filesystem = $this->_objectManager->get (\Magento\Framework\Filesystem::class);
        $directory = $filesystem->getDirectoryRead (DirectoryList::MEDIA);
        $fileName = CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER . '/' . ltrim ($file, '/');
        $path = $directory->getAbsolutePath ($fileName);
        if (mb_strpos ($path, '..') !== false
            || (!$directory->isFile ($fileName)
                && !$this->_objectManager->get (
                    \Magento\MediaStorage\Helper\File\Storage::class
                )->processStorageFile ($path))
        ) {
            throw new NotFoundException(__ ('Page not found.'));
        }

        if ($plain) {
            $extension = pathinfo ($path, PATHINFO_EXTENSION);
            switch (strtolower ($extension)) {
                case 'gif':
                    $contentType = 'image/gif';
                    break;
                case 'jpg':
                    $contentType = 'image/jpeg';
                    break;
                case 'png':
                    $contentType = 'image/png';
                    break;
                default:
                    $contentType = 'application/octet-stream';
                    break;
            }
            $stat = $directory->stat ($fileName);
            $contentLength = $stat['size'];
            $contentModify = $stat['mtime'];

            /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
            $resultRaw = $this->resultRawFactory->create ();
            $resultRaw->setHttpResponseCode (200)
                ->setHeader ('Pragma', 'public', true)
                ->setHeader ('Content-type', $contentType, true)
                ->setHeader ('Content-Length', $contentLength)
                ->setHeader ('Last-Modified', date ('r', $contentModify));
            $resultRaw->setContents ($directory->readFile ($fileName));
            return $resultRaw;
        } else {
            $name = pathinfo ($path, PATHINFO_BASENAME);
            $this->_fileFactory->create (
                $name,
                ['type' => 'filename', 'value' => $fileName],
                DirectoryList::MEDIA
            );
        }
    }

    /**
     * Get parameters from request.
     *
     * @return array
     * @throws NotFoundException
     */
    private function getFileParams()
    {
        if ($this->getRequest ()->getParam ('file')) {
            // download file
            $file = $this->urlDecoder->decode (
                $this->getRequest ()->getParam ('file')
            );

            return [$file, false];
        } elseif ($this->getRequest ()->getParam ('image')) {
            // show plain image
            $file = $this->urlDecoder->decode (
                $this->getRequest ()->getParam ('image')
            );

            return [$file, true];
        } else {
            throw new NotFoundException(__ ('Page not found.'));
        }
    }
}
