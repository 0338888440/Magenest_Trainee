<?php

namespace Magenest\ImportReview\Model\Source\Import\Behavior;

class Basic extends \Magento\ImportExport\Model\Source\Import\AbstractBehavior
{
    public function toOptionArray()
    {
        return parent::toOptionArray (); // TODO: Change the autogenerated stub
    }


    public function getNotes($entityCode)
    {
        return parent::getNotes ($entityCode); // TODO: Change the autogenerated stub
    }

    public function toArray()
    {
        return [
            \Magento\ImportExport\Model\Import::BEHAVIOR_APPEND => __ ('Add/Update'),
            \Magento\ImportExport\Model\Import::BEHAVIOR_REPLACE => __ ('Replace'),
            \Magento\ImportExport\Model\Import::BEHAVIOR_DELETE => __ ('Delete')
        ];
    }

    public function getCode()
    {
        return 'basic';
    }
}