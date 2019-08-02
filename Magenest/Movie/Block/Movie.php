<?php

namespace Magenest\Movie\Block;

use Magento\Framework\View\Element\Template;

class Movie extends Template
{
    protected $_movie;
    protected $_actor;
    protected $_director;
    protected $_movie_actor;

    public function __construct(Template\Context $context,
                                \Magenest\Movie\Model\ResourceModel\Movie\CollectionFactory $movie,
                                \Magenest\Movie\Model\ResourceModel\Actor\CollectionFactory $actor,
                                \Magenest\Movie\Model\ResourceModel\Director\CollectionFactory $director,
                                \Magenest\Movie\Model\ResourceModel\MovieActor\CollectionFactory $movie_actor,
                                array $data = [])
    {
        $this->_movie = $movie;
        $this->_actor = $actor;
        $this->_director = $director;
        $this->_movie_actor = $movie_actor;
        parent::__construct ($context, $data);
    }

    public function getMovie()
    {
        $collection = $this->_movie->create ()->addFieldToSelect ('*');

        return $collection;
    }

    public function getActor()
    {
        $collection = $this->_actor->create ()->addFieldToSelect ('*');


        return $collection;
    }

    public function getDirector()
    {
        $collection = $this->_director->create ()->addFieldToSelect ('*');

        return $collection;
    }

    public function getMovieActor()
    {
        $collection = $this->_movie_actor->create ()->addFieldToSelect ('*');

        return $collection;
    }
}