<?php
namespace Magenest\Movie\Block\System\Config\Form;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Button extends \Magento\Config\Block\System\Config\Form\Field
{
    const BUTTON_TEMPLATE = 'system/config/refresh.phtml';

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
            $this->setTemplate(static::BUTTON_TEMPLATE);
        return $this;
    }
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $this->addData(
            [
                'id'        => 'refresh_btn',
                'button_label'     => _('Refresh'),
            ]
        );
        return $this->_toHtml();
    }
}