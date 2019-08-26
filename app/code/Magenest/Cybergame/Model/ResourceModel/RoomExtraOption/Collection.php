<?php
namespace Magenest\Cybergame\Model\ResourceModel\RoomExtraOption;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {
    public function _construct() {
        $this->_init('Magenest\Cybergame\Model\RoomExtraOption', 'Magenest\Cybergame\Model\ResourceModel\RoomExtraOption');
    }
}