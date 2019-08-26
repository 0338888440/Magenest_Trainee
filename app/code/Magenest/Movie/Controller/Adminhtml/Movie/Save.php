<?php

namespace Magenest\Movie\Controller\Adminhtml\Movie;

class Save extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE = 'Movie';

    protected $resultPageFactory;
    protected $_movieFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magenest\Movie\Model\MovieFactory $movieFactory
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->_movieFactory = $movieFactory;
        parent::__construct ($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create ();
        $data = $this->getRequest ()->getPostValue ();

        if ($data) {
            $id = $data['movie_id'];
            $movie = $this->_movieFactory->create ()->load ($id);

            $data = array_filter ($data, function ($value) {
                return $value !== '';
            });

            $movie->setData ($data);
            $movie->save ();

            if (isset($data['actor_id'])) {
                $actorIds = $data['actor_id'];
                $movieId = null;
                if ($id == '') {
                    $movieId = $this->getNewId ();
                } else {
                    $movieId = $id;
                }
                $this->deleteAllRecordByMovieId ($movieId);
                for ($i = 0; $i < count ($actorIds); $i++) {
                    $movieActorModel = $this->_objectManager->create ('Magenest\Movie\Model\MovieActor');
                    $movieActorModel->setMovieId ($movieId);
                    $movieActorModel->setActorId ($actorIds[$i]);
                    $movieActorModel->save ();
                }
            }

            $this->messageManager->addSuccess (__ ('Successfully saved the item.'));
            $this->_objectManager->get ('Magento\Backend\Model\Session')->setFormData (false);

            //chap 7 event
            $this->_eventManager->dispatch ('save_movie', ['movie_id' => $this->getNewId ()]);

            return $resultRedirect->setPath ('*/*/');
        }

        return $resultRedirect->setPath ('*/*/');
    }
    private function getNewId()
    {
        $colNewId = $this->_movieFactory->create ()
            ->getCollection ()->addFieldToSelect ('movie_id')
            ->setOrder ('movie_id', 'DESC')
            ->setPageSize (1);
        $newId = $colNewId->getFirstItem ()->getData ('movie_id');
        return $newId;
    }

    private function deleteAllRecordByMovieId($movieId)
    {
        $sql = 'DELETE FROM magenest_movie_actor WHERE movie_id = ' . $movieId;
        $resource = $this->_objectManager->get ('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection ();
        $connection->query ($sql);
    }
}
