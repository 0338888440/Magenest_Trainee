<?php

namespace Magenest\Movie\Block\Adminhtml\Movie\Edit;

use Magento\Search\Block\Adminhtml\Synonyms\Edit\GenericButton;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveButton extends GenericButton implements ButtonProviderInterface
{
    public function getButtonData()
    {
        return [
            'label' => __ ('Save'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 90
        ];
    }

    public function getSaveUrl()
    {
        return $this->getUrl ('*/*/save', []);
    }
}
