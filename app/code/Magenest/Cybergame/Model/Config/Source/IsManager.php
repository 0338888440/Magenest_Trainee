<?php

namespace Magenest\Cybergame\Model\Config\Source;

class IsManager extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    public function getAllOptions()
    {
        return [
            ['label' => __('Manager'), 'value' => 1],
            ['label' => __('Other'), 'value' => 0]
        ];
        // TODO: Implement getAllOptions() method.
    }
}