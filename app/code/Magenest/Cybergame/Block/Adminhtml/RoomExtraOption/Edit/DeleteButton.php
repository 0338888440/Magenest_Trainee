<?php

namespace Magenest\Cybergame\Block\Adminhtml\RoomExtraOption\Edit;

use Magento\Search\Block\Adminhtml\Synonyms\Edit\GenericButton;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DeleteButton extends GenericButton implements ButtonProviderInterface
{
    public function getButtonData()
    {
        return [
            'label' => __ ('Delete'),
            'on_click' => 'deleteConfirm(\'' . __ ('Are you sure you want to delete this ?') . '\', \'' . $this->getDeleteUrl () . '\')',
            'class' => 'delete',
            'sort_order' => 20
        ];
    }

    public function getDeleteUrl()
    {
        $urlInterface = \Magento\Framework\App\ObjectManager::getInstance ()->get ('Magento\Framework\UrlInterface');
        $url = $urlInterface->getCurrentUrl ();

        $parts = explode ('/', parse_url ($url, PHP_URL_PATH));

        $id = $parts[6];

        return $this->getUrl ('*/*/delete', ['id' => $id]);
    }
}
