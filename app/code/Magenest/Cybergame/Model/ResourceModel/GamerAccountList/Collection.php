<?php
namespace Magenest\Cybergame\Model\ResourceModel\GamerAccountList;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {
    public function _construct() {
        $this->_init('Magenest\Cybergame\Model\GamerAccountList', 'Magenest\Cybergame\Model\ResourceModel\GamerAccountList');
    }
}