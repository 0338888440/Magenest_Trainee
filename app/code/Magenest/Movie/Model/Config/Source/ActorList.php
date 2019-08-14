<?php

namespace Magenest\Movie\Model\Config\Source;

class ActorList implements \Magento\Framework\Data\OptionSourceInterface
{
    private $_actorCollection;
    protected $_options;

    public function __construct(\Magenest\Movie\Model\ResourceModel\Actor\CollectionFactory $actorCollection
    )
    {
        $this->_actorCollection = $actorCollection;
    }

    public function toOptionArray()
    {
        if ($this->_options === null) {
            $collection = $this->_actorCollection->create ();

            $this->_options = [['label' => '', 'value' => '']];

            foreach ($collection as $act) {
                $this->_options[] = [
                    'label' => __ ('%1', $act->getData ('name')),
                    'value' => $act->getId ()
                ];
            }
        }
        return $this->_options;
    }
}