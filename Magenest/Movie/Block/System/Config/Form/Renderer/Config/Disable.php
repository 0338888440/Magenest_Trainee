<?php
namespace Magenest\Movie\Block\System\Config\Form\Renderer\Config;

class Disable extends \Magento\Config\Block\System\Config\Form\Field
{
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $element->setReadonly (true);
        return $element->getElementHtml ();
    }
}