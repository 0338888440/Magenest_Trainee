<?php

namespace Magenest\Movie\Ui\DataProvider\Actor\Form;

use Magenest\Movie\Model\ResourceModel\Actor\CollectionFactory;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $collection;
    protected $_loadedData;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $actorCollectionFactory,
        array $meta = [],
        array $data = []
    )
    {
        $this->collection = $actorCollectionFactory->create ();
        parent::__construct ($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        }

        $items = $this->collection->getItems ();

        foreach ($items as $actor) {
            $this->_loadedData[$actor->getId ()] = $actor->getData ();
        }

        return $this->_loadedData;
    }
}