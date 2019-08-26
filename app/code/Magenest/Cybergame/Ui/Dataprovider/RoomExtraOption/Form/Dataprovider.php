<?php

namespace Magenest\Cybergame\Ui\DataProvider\RoomExtraOption\Form;

use Magenest\Cybergame\Model\ResourceModel\RoomExtraOption\CollectionFactory;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $collection;
    protected $_loadedData;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $roomExtraOption,
        array $meta = [],
        array $data = [])
    {
        $this->collection = $roomExtraOption->create ();
        parent::__construct ($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }
    public function getData()
    {
        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        }

        $items = $this->collection->getItems ();

        foreach ($items as $item) {
            $this->_loadedData[$item->getId ()] = $item->getData ();
        }
        return $this->_loadedData;
    }
}