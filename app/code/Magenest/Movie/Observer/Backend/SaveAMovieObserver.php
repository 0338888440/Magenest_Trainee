<?php

namespace Magenest\Movie\Observer\Backend;

use Magento\Framework\Event\ObserverInterface;
use Magenest\Movie\Model\ResourceModel\Movie\CollectionFactory;

class SaveAMovieObserver implements ObserverInterface
{
    /** @var \Psr\Log\LoggerInterface $logger */
    protected $logger;
    protected $_movie;

    public function __construct(\Psr\Log\LoggerInterface $logger,
                                CollectionFactory $movie)
    {
        $this->_movie = $movie;
        $this->logger = $logger;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->logger->debug ('Save A Movie');

        $newId = $observer->getData ('movie_id');
        $movie = $this->_movie->create ()->addFieldToSelect (['movie_id', 'rating'])
            ->addFieldToFilter ('movie_id', $newId);
        $movie->setDataToAll ('rating', 0);
        $movie->save ();
    }
}