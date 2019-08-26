<?php
namespace Magenest\Cybergame\Model\ResourceModel;
class RoomExtraOption extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb {
    public function _construct() {
        $this->_init('room_extra_option', 'id');
    }
}