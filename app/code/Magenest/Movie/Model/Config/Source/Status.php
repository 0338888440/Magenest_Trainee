<?php

namespace Magenest\Movie\Model\Config\Source;

class Status extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    public function getAllOptions()
    {
        return [
            ['label' => __('Enable'), 'value' => 1],
            ['label' => __('Disable'), 'value' => 2]
        ];
        // TODO: Implement getAllOptions() method.
    }
}