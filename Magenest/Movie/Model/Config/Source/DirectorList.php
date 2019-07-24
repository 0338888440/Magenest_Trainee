<?php

namespace Magenest\Movie\Model\Config\Source;

class DirectorList implements \Magento\Framework\Data\OptionSourceInterface
{
    private  $_directorCollection;
    protected $_options;
    /**
     * Return array of options as value-label pairs, eg. value => label
     *
     * @return array
     */

    /**
     * @return mixed
     */
    public function __construct( \Magenest\Movie\Model\ResourceModel\Director\CollectionFactory $directorCollection
    ){
        $this->_directorCollection = $directorCollection;
    }


    public function toOptionArray()
    {
        if ($this->_options === null) {
            $collection = $this->_directorCollection->create();

            $this->_options = [['label' => '', 'value' => '']];

            foreach ($collection as $dir) {
                $this->_options[] = [
                    'label' => __('%1', $dir->getData('name')),
                    'value' => $dir->getId()
                ];
            }
        }
        return $this->_options;
    }
}