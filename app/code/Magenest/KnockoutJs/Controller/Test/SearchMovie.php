<?php
namespace Magenest\KnockoutJs\Controller\Test;

class SearchMovie extends \Magento\Framework\App\Action\Action
{

    protected $resultPageFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function execute()
    {

        $movieModel = $this->_objectManager->create('Magenest\Movie\Model\Movie');
        /*
         * @var \Magenest\Movie\Model\ResourceModel\Movie\Collection $movieCollection
         * */
        $nameRequest = $this->getRequest()->getParam('name');
        $movieCollection = $movieModel->getCollection();
        if ($nameRequest != "") {
            $movieCollection = $movieCollection->AddFieldToFilter('name', ['like' => "%".$nameRequest."%"]);
        }
        $movieResult = $movieCollection->getItems();
        $movieList = [];
        foreach ($movieResult as $movie) {
            $movieObject = [
                'movie_id' => $movie->getData('movie_id'),
                'name' => $movie->getData('name'),
                'description' => $movie->getData('description'),
                'rating' => $movie->getData('rating')
            ];
            $movieList[] = $movieObject;
        }
        echo json_encode($movieList);
    }
}