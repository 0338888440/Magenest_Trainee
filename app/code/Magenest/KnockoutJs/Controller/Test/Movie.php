<?php
namespace Magenest\KnockoutJs\Controller\Test;

use Magento\Store\Model\StoreManager;


class Movie extends \Magento\Framework\App\Action\Action
{
    protected $_movieFactory;
    protected $_storeManager;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magenest\Movie\Model\ResourceModel\Movie\CollectionFactory $movieFactory,
        StoreManager $storeManager)
    {
        $this->_movieFactory = $movieFactory;
        $this->_storeManager = $storeManager;
        parent::__construct($context);
    }

    public function getCollection()
    {
        return $this->_movieFactory->create()
            ->addFieldToSelect('*')
            ->setPageSize(5)
            ->setCurPage(1);
    }

    public function execute()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $movie = $this->_movieFactory->create()->addFieldToSelect ('*');
            $movie->getSelect ()->join (
                ['d' => 'magenest_director'],
                'main_table.director_id = d.director_id',
                'd.name as director_name'
            );
            foreach ($movie as $item) {
                if ($item->getData('movie_id') == $id){
                    $movieData = [
                        'movie_id' => $item->getData('movie_id'),
                        'name' => $item->getData('name'),
                        'description' => $item->getData('description'),
                        'rating' => $item->getData ('rating'),
                        'director_name' => $item->getData ('director_name'),
                    ];
                }
            }
            echo json_encode($movieData);
            return;
        }
    }
}