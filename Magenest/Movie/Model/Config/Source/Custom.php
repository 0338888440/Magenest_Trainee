<?php

namespace Magenest\Movie\Model\Config\Source;

class Custom implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            '1' => 'Show',
            '0' => 'Hide',
        ];
    }
}