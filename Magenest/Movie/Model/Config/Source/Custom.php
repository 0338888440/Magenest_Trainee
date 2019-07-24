<?php

namespace Magenest\Movie\Model\Config\Source;

class Custom implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Return array of options as value-label pairs, eg. value => label
     *
     * @return array
     */

    public function toOptionArray()
    {
        return [
            '1' => 'Show',
            '0' => 'Hide',
        ];
    }
}