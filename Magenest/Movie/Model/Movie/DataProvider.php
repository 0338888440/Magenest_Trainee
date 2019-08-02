<?php

namespace Magenest\Movie\Model\Movie;

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
            ['d' => 'magenest_director'],
            'main_table.director_id = d.director_id',
            'd.name as director_name'
        );
        parent::__construct ($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }
}