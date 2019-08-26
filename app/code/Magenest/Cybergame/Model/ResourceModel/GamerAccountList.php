<?php
namespace Magenest\Cybergame\Model\ResourceModel;
class GamerAccountList extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb {
    public function _construct() {
        $this->_init('gamer_account_list', 'id');
    }
}