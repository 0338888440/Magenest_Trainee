<?php

namespace Magenest\Movie\Ui\DataProvider\Movie\Form;

use Magenest\Movie\Model\ResourceModel\Movie\CollectionFactory;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $_loadedData;
    protected $collection;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $movie,
        array $meta = [],
        array $data = [])
    {
        $this->collection = $movie->create ();
        $this->collection->getSelect ()->join (
            ['md' => 'magenest_director'],
            'main_table.director_id = md.director_id',
            'md.name as director_name'
        );
        parent::__construct ($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        }

        $items = $this->collection->getItems ();

        foreach ($items as $movie) {
            $this->_loadedData[$movie->getId ()] = $movie->getData ();
        }

        return $this->_loadedData;
    }
}