<?php

namespace Magenest\Cybergame\Block\Adminhtml\RoomExtraOption\Edit;

use Magento\Search\Block\Adminhtml\Synonyms\Edit\GenericButton;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class ResetButton extends GenericButton implements ButtonProviderInterface
{
    public function getButtonData()
    {
        return [
            'label' => __ ('Reset'),
            'on_click' => 'javascript: location.reload();',
            'class' => 'reset',
            'sort_order' => 30
        ];
    }
}